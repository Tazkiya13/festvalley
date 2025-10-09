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
use Illuminate\Support\Facades\Route;

class BookingUrlTest extends TestCase
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
    public function all_booking_routes_are_defined_correctly()
    {
        // Create test email for approval routes
        $email = Email::create([
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
            'invoice_id' => null,
            'order_id' => null,
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '123456789',
            'event_type' => 'Wedding',
            'event_date' => now()->addDays(30)->format('Y-m-d'),
            'event_location' => 'Test Location',
            'event_description' => 'Test Event',
            'special_requirements' => 'None',
            'subject' => 'Test Subject',
            'body' => 'Test Body',
            'status' => 'waiting',
            'sent_at' => now(),
        ]);

        $routes = [
            // Public routes
            ['GET', 'book/' . $this->package->id, 'booking.form'],
            ['POST', 'booking', 'booking.store'],
            
            // Protected routes  
            ['GET', 'booking', 'booking.index'],
            
            // Approval routes (should be accessible via URL)
            ['GET', 'booking/approve/' . $email->id, 'booking.approve'],
            ['GET', 'booking/reject/' . $email->id, 'booking.reject'],
        ];

        foreach ($routes as [$method, $uri, $routeName]) {
            // Test that route exists
            $this->assertTrue(
                Route::has($routeName),
                "Route '{$routeName}' does not exist"
            );
            
            // Test that URL is accessible (might return auth errors, but not 404)
            if ($method === 'GET') {
                $response = $this->get($uri);
                $this->assertNotEquals(404, $response->getStatusCode(), 
                    "Route '{$uri}' returned 404");
            }
        }
    }

    /** @test */
    public function booking_form_url_structure_is_correct()
    {
        // Test with valid package ID
        $response = $this->get("/book/{$this->package->id}");
        $response->assertStatus(200);
        
        // Test with invalid package ID
        $response = $this->get("/book/999999");
        $response->assertStatus(404);
        
        // Test URL without parameter
        $response = $this->get("/book/");
        $response->assertStatus(404);
    }

    /** @test */
    public function booking_confirmation_url_works()
    {
        $invoice = Invoice::factory()->create([
            'user_id' => $this->customer->id,
            'package_id' => $this->package->id,
        ]);

        // Test correct URL structure
        $response = $this->get("/booking/confirmation/{$invoice->id}");
        $response->assertStatus(200);
        
        // Test invalid invoice ID
        $response = $this->get("/booking/confirmation/999999");
        $response->assertStatus(404);
    }

    /** @test */
    public function booking_payment_url_works()
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

        // Test correct URL structure
        $response = $this->get("/booking/payment/{$invoice->id}");
        $response->assertStatus(200);
        
        // Test invalid invoice ID
        $response = $this->get("/booking/payment/999999");
        $response->assertStatus(404);
    }

    /** @test */
    public function landing_page_urls_work()
    {
        $response = $this->get('/landingpage');
        $response->assertStatus(200);
        
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function package_detail_url_works()
    {
        // Test public package detail
        $response = $this->get("/card/detail/{$this->package->id}");
        $response->assertStatus(200);
        
        // Test invalid package ID
        $response = $this->get("/card/detail/999999");
        $response->assertStatus(404);
    }

    /** @test */
    public function dashboard_urls_require_authentication()
    {
        $protectedUrls = [
            '/booking',      // Booking list
            '/card-list',    // Customer dashboard
            '/artist',       // Artist dashboard
            '/package',        // Admin package management
            '/order',        // Order management
            '/invoice',      // Invoice management
        ];

        foreach ($protectedUrls as $url) {
            $response = $this->get($url);
            
            // Should redirect to login (302) or show auth error (401/403)
            $this->assertContains($response->getStatusCode(), [302, 401, 403], 
                "URL '{$url}' should require authentication");
        }
    }

    /** @test */
    public function booking_urls_redirect_correctly_after_auth()
    {
        // Test customer access to customer pages
        $response = $this->actingAs($this->customer)->get('/card-list');
        $response->assertStatus(200);
        
        // Test artist access to artist pages
        $response = $this->actingAs($this->artist)->get('/artist');
        $response->assertStatus(200);
        
        // Test booking list access
        $response = $this->actingAs($this->customer)->get('/booking');
        $response->assertStatus(200);
    }

    /** @test */
    public function email_approval_urls_work_without_auth()
    {
        // Create an email record
        $email = Email::factory()->create([
            'user_id' => $this->artist->id,
            'package_id' => $this->package->id,
            'status' => 'waiting',
        ]);

        // Test approve URL
        $response = $this->get("/booking/approve/{$email->id}");
        $response->assertStatus(200); // Should return JSON success
        
        // Test reject URL  
        $email->update(['status' => 'waiting']); // Reset status
        $response = $this->get("/booking/reject/{$email->id}");
        $response->assertStatus(200); // Should return JSON success
    }

    /** @test */
    public function route_names_are_consistent()
    {
        $expectedRoutes = [
            'booking.form',
            'booking.store',
            'booking.index',
            'booking.confirmation',
            'booking.payment',
            'booking.process-payment',
            'booking.approve',
            'booking.reject',
            'landingpage.discover',
            'home',
            'card.detail',
            'card.list',
            'artists.index',
        ];

        foreach ($expectedRoutes as $routeName) {
            $this->assertTrue(
                Route::has($routeName),
                "Expected route '{$routeName}' does not exist"
            );
        }
    }

    /** @test */
    public function booking_urls_handle_missing_parameters_gracefully()
    {
        // URLs that should return 404 for missing parameters
        $urlsWithMissingParams = [
            '/book',
            '/booking/confirmation',
            '/booking/payment', 
            '/booking/approve',
            '/booking/reject',
            '/card/detail',
        ];

        foreach ($urlsWithMissingParams as $url) {
            $response = $this->get($url);
            
            // Should return 404 or redirect, not 500
            $this->assertNotEquals(500, $response->getStatusCode(),
                "URL '{$url}' returned 500 error for missing parameter");
        }
    }

    /** @test */
    public function post_routes_require_csrf_protection()
    {
        // Test booking store without CSRF token
        $response = $this->post('/booking', [
            'package_id' => $this->package->id,
        ]);
        
        // Should return 419 (CSRF token mismatch) not process the request
        $this->assertEquals(419, $response->getStatusCode());
    }

    /** @test */
    public function booking_routes_support_different_http_methods()
    {
        $routeMethodTests = [
            ['GET', '/book/' . $this->package->id],
            ['POST', '/booking'],
            ['GET', '/booking'],
            // Payment routes support both GET and POST
            ['GET', '/booking/payment/1'], // Will 404 but method is allowed
            ['POST', '/booking/payment/1'], // Will 404 but method is allowed
        ];

        foreach ($routeMethodTests as [$method, $uri]) {
            $response = $this->call($method, $uri, [], [], [], ['HTTP_X_CSRF_TOKEN' => 'test']);
            
            // Should not return 405 (Method Not Allowed)
            $this->assertNotEquals(405, $response->getStatusCode(),
                "Route '{$uri}' does not support {$method} method");
        }
    }

    /** @test */
    public function url_generation_works_correctly()
    {
        // Test URL generation with parameters
        $bookingFormUrl = route('booking.form', $this->package->id);
        $this->assertStringContainsString('book/' . $this->package->id, $bookingFormUrl);
        
        // Test that generated URLs are accessible
        $response = $this->get($bookingFormUrl);
        $response->assertStatus(200);
        
        // Test confirmation URL generation
        $invoice = Invoice::factory()->create([
            'user_id' => $this->customer->id,
            'package_id' => $this->package->id,
        ]);
        
        $confirmationUrl = route('booking.confirmation', $invoice->id);
        $response = $this->get($confirmationUrl);
        $response->assertStatus(200);
    }
}
