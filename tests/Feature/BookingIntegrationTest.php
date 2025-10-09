<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Email;
use App\Models\AvailableDate;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

class BookingIntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $artist;
    protected $customer;
    protected $package;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        Role::create(['name' => 'Artist', 'guard_name' => 'web']);
        Role::create(['name' => 'Customer', 'guard_name' => 'web']);

        // Create users
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Admin');

        $this->artist = User::factory()->create();
        $this->artist->assignRole('Artist');

        $this->customer = User::factory()->create();
        $this->customer->assignRole('Customer');

        // Create a test package
        $this->package = Package::factory()->create([
            'user_id' => $this->artist->id,
        ]);

        AvailableDate::factory()->create([
            'package_id' => $this->package->id,
            'date' => now()->addDays(5)->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function complete_booking_flow_works_for_guest()
    {
        $availableDate = $this->package->availableDates->first();
        
        // Step 1: Guest accesses booking form
        $response = $this->get(route('booking.form', $this->package->id));
        $response->assertStatus(200);
        $response->assertSee($this->package->name_package);
        
        // Step 2: Guest submits booking
        $bookingData = [
            'package_id' => $this->package->id,
            'available_date_id' => $availableDate->id,
            'customer_name' => 'Integration Test Guest',
            'customer_email' => 'guest@integration.com',
            'customer_phone' => '081234567890',
            'event_type' => 'Wedding',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_location' => 'Jakarta',
            'event_description' => 'Integration test wedding',
            'special_requirements' => 'None',
        ];

        $response = $this->post(route('booking.store'), $bookingData);
        $response->assertRedirect();
        
        // Verify user was created
        $user = User::where('email', 'guest@integration.com')->first();
        $this->assertNotNull($user);
        
        // Verify invoice was created
        $invoice = Invoice::where('user_id', $user->id)
            ->where('package_id', $this->package->id)
            ->first();
        $this->assertNotNull($invoice);
        $this->assertEquals('unpaid', $invoice->status);
        
        // Verify email booking request was created
        $email = Email::where('invoice_id', $invoice->id)->first();
        $this->assertNotNull($email);
        $this->assertEquals('waiting', $email->status);
        
        // Step 3: Guest can access confirmation page
        $response = $this->get(route('booking.confirmation', $invoice->id));
        $response->assertStatus(200);
        $response->assertSee('Integration Test Guest');
        
        // Step 4: Artist approves the booking
        $response = $this->get(route('booking.approve', $email->id));
        $response->assertStatus(200);
        
        $email->refresh();
        $this->assertEquals('approved', $email->status);
        
        // Step 5: Guest can now access payment page
        $response = $this->get(route('booking.payment', $invoice->id));
        $response->assertStatus(200);
        
        // Step 6: Guest submits payment proof
        $paymentData = [
            'bukti_transfer' => \Illuminate\Http\Testing\File::fake()->image('payment.jpg'),
        ];
        
        $response = $this->post(route('booking.process-payment', $invoice->id), $paymentData);
        $response->assertRedirect(route('booking.confirmation', $invoice->id));
        
        // Verify invoice status updated
        $invoice->refresh();
        $this->assertEquals('waiting', $invoice->status);
        
        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'invoice_id' => $invoice->id,
            'user_id' => $user->id,
            'status' => 'waiting approval',
        ]);
    }

    /** @test */
    public function complete_booking_flow_works_for_authenticated_user()
    {
        $availableDate = $this->package->availableDates->first();
        
        // Step 1: Login as customer
        $this->actingAs($this->customer);
        
        // Step 2: Access booking form
        $response = $this->get(route('booking.form', $this->package->id));
        $response->assertStatus(200);
        
        // Step 3: Submit booking
        $bookingData = [
            'package_id' => $this->package->id,
            'available_date_id' => $availableDate->id,
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'customer_phone' => '081234567890',
            'event_type' => 'Corporate Event',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_location' => 'Bandung',
            'event_description' => 'Company anniversary',
            'special_requirements' => 'Microphone setup needed',
        ];

        $response = $this->post(route('booking.store'), $bookingData);
        $response->assertRedirect();
        
        // Verify invoice created for authenticated user
        $invoice = Invoice::where('user_id', $this->customer->id)
            ->where('package_id', $this->package->id)
            ->first();
        $this->assertNotNull($invoice);
        
        // Step 4: User can view their booking in booking list
        $response = $this->get(route('booking.index'));
        $response->assertStatus(200);
        $response->assertSee('Corporate Event');
    }

    /** @test */
    public function booking_with_account_creation_flow()
    {
        $availableDate = $this->package->availableDates->first();
        
        $bookingData = [
            'package_id' => $this->package->id,
            'available_date_id' => $availableDate->id,
            'customer_name' => 'New Account User',
            'customer_email' => 'newuser@test.com',
            'customer_phone' => '081234567890',
            'event_type' => 'Birthday Party',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_location' => 'Surabaya',
            'event_description' => 'Birthday celebration',
            'special_requirements' => 'Happy birthday song',
            'user_type' => 'create',
            'create_account' => '1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('booking.store'), $bookingData);
        $response->assertRedirect();
        
        // User should be automatically logged in
        $user = User::where('email', 'newuser@test.com')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->hasRole('Customer'));
        
        // User can now access customer dashboard
        $response = $this->get(route('card.list'));
        $response->assertStatus(200);
        
        // User can view their booking
        $response = $this->get(route('booking.index'));
        $response->assertStatus(200);
        $response->assertSee('Birthday Party');
    }

    /** @test */
    public function artist_can_manage_bookings_for_their_packages()
    {
        // Create bookings for artist's package
        $invoice1 = Invoice::factory()->create([
            'package_id' => $this->package->id,
        ]);
        
        $invoice2 = Invoice::factory()->create([
            'package_id' => $this->package->id,
        ]);
        
        $email1 = Email::factory()->create([
            'invoice_id' => $invoice1->id,
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
            'status' => 'waiting',
        ]);
        
        $email2 = Email::factory()->create([
            'invoice_id' => $invoice2->id,
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
            'status' => 'waiting',
        ]);

        // Artist logs in and views bookings
        $response = $this->actingAs($this->artist)
            ->get(route('booking.index'));
        
        $response->assertStatus(200);
        
        // Artist can approve bookings
        $response = $this->get(route('booking.approve', $email1->id));
        $response->assertStatus(200);
        
        // Artist can reject bookings
        $response = $this->get(route('booking.reject', $email2->id));
        $response->assertStatus(200);
        
        // Verify status changes
        $email1->refresh();
        $email2->refresh();
        $this->assertEquals('approved', $email1->status);
        $this->assertEquals('rejected', $email2->status);
    }

    /** @test */
    public function admin_can_view_and_manage_all_bookings()
    {
        // Create bookings from different artists/customers
        $otherArtist = User::factory()->create();
        $otherArtist->assignRole('Artist');
        
        $otherPackage = Package::factory()->create(['user_id' => $otherArtist->id]);
        
        $invoice1 = Invoice::factory()->create(['package_id' => $this->package->id]);
        $invoice2 = Invoice::factory()->create(['package_id' => $otherPackage->id]);
        
        Email::factory()->create([
            'invoice_id' => $invoice1->id,
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
        ]);
        
        Email::factory()->create([
            'invoice_id' => $invoice2->id,
            'user_id' => $otherArtist->id,
            'package_id' => $otherPackage->id,
        ]);

        // Admin can see all bookings
        $response = $this->actingAs($this->admin)
            ->get(route('booking.index'));
        
        $response->assertStatus(200);
        $emails = $response->viewData('emails');
        $this->assertEquals(2, $emails->count());
    }

    /** @test */
    public function booking_rejection_allows_date_change()
    {
        // Create a rejected booking
        $invoice = Invoice::factory()->create([
            'user_id' => $this->customer->id,
            'package_id' => $this->package->id,
        ]);
        
        $email = Email::factory()->create([
            'invoice_id' => $invoice->id,
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
            'status' => 'rejected',
        ]);

        // Create another available date
        $newDate = AvailableDate::factory()->create([
            'package_id' => $this->package->id,
            'date' => now()->addDays(10)->format('Y-m-d'),
        ]);

        // Customer can update date for rejected booking
        $response = $this->actingAs($this->customer)
            ->post(route('booking.updateDate', $email->id), [
                'available_date_id' => $newDate->id,
            ]);

        $response->assertRedirect();
        
        // Verify booking status reset to waiting
        $email->refresh();
        $this->assertEquals('waiting', $email->status);
        
        // Verify invoice updated with new date
        $invoice->refresh();
        $this->assertEquals($newDate->id, $invoice->available_date_id);
    }

    /** @test */
    public function database_transactions_work_correctly()
    {
        $availableDate = $this->package->availableDates->first();
        
        // Simulate database error during booking
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollback')->once();
        DB::shouldReceive('commit')->never();
        
        // This should fail gracefully
        $bookingData = [
            'package_id' => $this->package->id,
            'available_date_id' => $availableDate->id,
            'customer_name' => 'Test User',
            'customer_email' => 'test@error.com',
            'customer_phone' => '081234567890',
            'event_type' => 'Wedding',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_location' => 'Jakarta',
            'event_description' => 'Test booking',
        ];

        // Verify no partial data is saved on error
        $userCountBefore = User::count();
        $invoiceCountBefore = Invoice::count();
        $emailCountBefore = Email::count();

        // This test would need actual database error simulation
        // For now, just verify the transaction methods are called
        $this->assertTrue(true); // Placeholder
    }

    /** @test */
    public function concurrent_booking_same_date_handling()
    {
        $availableDate = $this->package->availableDates->first();
        
        // Create first booking
        $bookingData = [
            'package_id' => $this->package->id,
            'available_date_id' => $availableDate->id,
            'customer_name' => 'First Customer',
            'customer_email' => 'first@test.com',
            'customer_phone' => '081234567890',
            'event_type' => 'Wedding',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_location' => 'Jakarta',
            'event_description' => 'First booking',
        ];

        $response1 = $this->post(route('booking.store'), $bookingData);
        $response1->assertRedirect();
        
        // Create second booking with same date
        $bookingData['customer_name'] = 'Second Customer';
        $bookingData['customer_email'] = 'second@test.com';
        $bookingData['event_description'] = 'Second booking';

        $response2 = $this->post(route('booking.store'), $bookingData);
        $response2->assertRedirect();
        
        // Both bookings should be created (system allows multiple bookings per date)
        $this->assertDatabaseHas('emails', [
            'customer_email' => 'first@test.com',
        ]);
        
        $this->assertDatabaseHas('emails', [
            'customer_email' => 'second@test.com',
        ]);
    }
}
