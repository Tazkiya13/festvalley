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

class BookingAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $artist;
    protected $customer;
    protected $guest;
    protected $package;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles and permissions
        $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $artistRole = Role::create(['name' => 'Artist', 'guard_name' => 'web']);
        $customerRole = Role::create(['name' => 'Customer', 'guard_name' => 'web']);

        // Create users with different roles
        $this->admin = User::factory()->create(['email' => 'admin@test.com']);
        $this->admin->assignRole('Admin');

        $this->artist = User::factory()->create(['email' => 'artist@test.com']);
        $this->artist->assignRole('Artist');

        $this->customer = User::factory()->create(['email' => 'customer@test.com']);
        $this->customer->assignRole('Customer');

        // Create a test package
        $this->package = Package::factory()->create([
            'user_id' => $this->artist->id,
        ]);

        // Create available dates
        AvailableDate::factory()->create([
            'package_id' => $this->package->id,
            'date' => now()->addDays(5)->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function guest_can_access_booking_form()
    {
        $response = $this->get(route('booking.form', $this->package->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('booking.form');
    }

    /** @test */
    public function all_user_types_can_access_booking_form()
    {
        // Guest
        $response = $this->get(route('booking.form', $this->package->id));
        $response->assertStatus(200);

        // Customer
        $response = $this->actingAs($this->customer)
            ->get(route('booking.form', $this->package->id));
        $response->assertStatus(200);

        // Artist
        $response = $this->actingAs($this->artist)
            ->get(route('booking.form', $this->package->id));
        $response->assertStatus(200);

        // Admin
        $response = $this->actingAs($this->admin)
            ->get(route('booking.form', $this->package->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_can_make_booking_without_login()
    {
        $availableDate = $this->package->availableDates->first();
        
        $bookingData = [
            'package_id' => $this->package->id,
            'available_date_id' => $availableDate->id,
            'customer_name' => 'Guest User',
            'customer_email' => 'guest@test.com',
            'customer_phone' => '081234567890',
            'event_type' => 'Wedding',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_location' => 'Jakarta',
            'event_description' => 'Wedding ceremony',
        ];

        $response = $this->post(route('booking.store'), $bookingData);
        
        // Should succeed and redirect to confirmation
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'email' => 'guest@test.com'
        ]);
    }

    /** @test */
    public function authenticated_user_booking_uses_existing_account()
    {
        $availableDate = $this->package->availableDates->first();
        
        $bookingData = [
            'package_id' => $this->package->id,
            'available_date_id' => $availableDate->id,
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'customer_phone' => '081234567890',
            'event_type' => 'Wedding',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_location' => 'Jakarta',
            'event_description' => 'Wedding ceremony',
        ];

        $userCountBefore = User::count();

        $response = $this->actingAs($this->customer)
            ->post(route('booking.store'), $bookingData);
        
        $response->assertStatus(302);
        
        // Should not create a new user
        $this->assertEquals($userCountBefore, User::count());
        
        // Should create invoice for existing user
        $this->assertDatabaseHas('invoices', [
            'user_id' => $this->customer->id,
            'package_id' => $this->package->id,
        ]);
    }

    /** @test */
    public function booking_list_requires_authentication()
    {
        $response = $this->get(route('booking.index'));
        
        $response->assertStatus(302); // Should redirect to login
        $response->assertRedirect(route('home'));
    }

    /** @test */
    public function admin_can_view_all_bookings()
    {
        // Create some bookings
        $invoice1 = Invoice::factory()->create(['package_id' => $this->package->id]);
        $invoice2 = Invoice::factory()->create(['package_id' => $this->package->id]);
        
        Email::factory()->create([
            'invoice_id' => $invoice1->id,
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
        ]);
        
        Email::factory()->create([
            'invoice_id' => $invoice2->id,
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('booking.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('booking.index');
        
        // Should see all bookings
        $response->assertViewHas('emails');
    }

    /** @test */
    public function artist_can_only_view_their_package_bookings()
    {
        // Create another artist and package
        $otherArtist = User::factory()->create();
        $otherArtist->assignRole('Artist');
        $otherPackage = Package::factory()->create(['user_id' => $otherArtist->id]);
        
        // Create bookings for both packages
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

        $response = $this->actingAs($this->artist)
            ->get(route('booking.index'));
        
        $response->assertStatus(200);
        $emails = $response->viewData('emails');
        
        // Should only see bookings for their own packages
        $this->assertEquals(1, $emails->count());
        $this->assertEquals($this->package->id, $emails->first()->package_id);
    }

    /** @test */
    public function customer_can_only_view_their_own_bookings()
    {
        // Create another customer
        $otherCustomer = User::factory()->create();
        $otherCustomer->assignRole('Customer');
        
        // Create bookings for both customers
        $invoice1 = Invoice::factory()->create([
            'user_id' => $this->customer->id,
            'package_id' => $this->package->id,
        ]);
        $invoice2 = Invoice::factory()->create([
            'user_id' => $otherCustomer->id,
            'package_id' => $this->package->id,
        ]);
        
        Email::factory()->create([
            'invoice_id' => $invoice1->id,
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
        ]);
        
        Email::factory()->create([
            'invoice_id' => $invoice2->id,
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('booking.index'));
        
        $response->assertStatus(200);
        $emails = $response->viewData('emails');
        
        // Should only see their own bookings
        $this->assertEquals(1, $emails->count());
        $this->assertEquals($this->customer->id, $emails->first()->invoice->user_id);
    }

    /** @test */
    public function booking_confirmation_access_control_works()
    {
        $invoice = Invoice::factory()->create([
            'user_id' => $this->customer->id,
            'package_id' => $this->package->id,
        ]);

        // Owner can access
        $response = $this->actingAs($this->customer)
            ->get(route('booking.confirmation', $invoice->id));
        $response->assertStatus(200);

        // Guest can access (for guest bookings)
        $response = $this->get(route('booking.confirmation', $invoice->id));
        $response->assertStatus(200);

        // Other authenticated users cannot access
        $otherCustomer = User::factory()->create();
        $otherCustomer->assignRole('Customer');
        
        $response = $this->actingAs($otherCustomer)
            ->get(route('booking.confirmation', $invoice->id));
        $response->assertStatus(403);
    }

    /** @test */
    public function payment_access_control_works()
    {
        $invoice = Invoice::factory()->create([
            'user_id' => $this->customer->id,
            'package_id' => $this->package->id,
        ]);
        
        Email::factory()->create([
            'invoice_id' => $invoice->id,
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
            'status' => 'approved',
        ]);

        // Owner can access
        $response = $this->actingAs($this->customer)
            ->get(route('booking.payment', $invoice->id));
        $response->assertStatus(200);

        // Guest can access (for guest bookings)  
        $response = $this->get(route('booking.payment', $invoice->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function booking_approval_routes_work()
    {
        $email = Email::factory()->create([
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
            'status' => 'waiting',
        ]);

        // Approval route should work
        $response = $this->get(route('booking.approve', $email->id));
        $response->assertStatus(200); // Returns JSON response
        
        $email->refresh();
        $this->assertEquals('approved', $email->status);

        // Reset status
        $email->update(['status' => 'waiting']);

        // Rejection route should work
        $response = $this->get(route('booking.reject', $email->id));
        $response->assertStatus(200); // Returns JSON response
        
        $email->refresh();
        $this->assertEquals('rejected', $email->status);
    }

    /** @test */
    public function public_package_detail_is_accessible_to_everyone()
    {
        // Guest
        $response = $this->get(route('card.detail', $this->package->id));
        $response->assertStatus(200);

        // Authenticated users
        $response = $this->actingAs($this->customer)
            ->get(route('card.detail', $this->package->id));
        $response->assertStatus(200);

        $response = $this->actingAs($this->artist)
            ->get(route('card.detail', $this->package->id));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)
            ->get(route('card.detail', $this->package->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function landing_page_is_accessible_to_everyone()
    {
        // Guest
        $response = $this->get(route('landingpage.discover'));
        $response->assertStatus(200);

        // Authenticated users
        $response = $this->actingAs($this->customer)
            ->get(route('landingpage.discover'));
        $response->assertStatus(200);
    }

    /** @test */
    public function dashboard_redirects_work_correctly_after_login()
    {
        // Test customer dashboard access
        $response = $this->actingAs($this->customer)
            ->get(route('card.list'));
        $response->assertStatus(200);

        // Test artist dashboard access  
        $response = $this->actingAs($this->artist)
            ->get(route('artists.index'));
        $response->assertStatus(200);

        // Admin dashboard should work for admin
        // Note: This might need permission setup in real scenario
    }
}
