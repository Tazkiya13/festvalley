<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Email;
use App\Models\AvailableDate;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BookingTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get Customer role
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);
        $artistRole = Role::firstOrCreate(['name' => 'Artist']);

        // Create test customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@test.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$customer->hasRole('Customer')) {
            $customer->assignRole($customerRole);
        }

        // Create test artist
        $artist = User::firstOrCreate(
            ['email' => 'artist@test.com'],
            [
                'name' => 'Test Artist',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$artist->hasRole('Artist')) {
            $artist->assignRole($artistRole);
        }

        // Create test packages
        $package1 = Package::firstOrCreate([
            'package_name' => 'Wedding Music Package',
            'user_id' => $artist->id,
            'price' => 5000000,
            'description' => 'Perfect music package for wedding ceremonies',
            'image' => 'packages/wedding-music.jpg'
        ]);

        $package2 = Package::firstOrCreate([
            'package_name' => 'Birthday Party Package',
            'user_id' => $artist->id,
            'price' => 2500000,
            'description' => 'Fun music package for birthday parties',
            'image' => 'packages/birthday-party.jpg'
        ]);

        // Create available dates
        $availableDate1 = AvailableDate::firstOrCreate([
            'package_id' => $package1->id,
            'date' => Carbon::now()->addDays(30)->format('Y-m-d'),
        ]);

        $availableDate2 = AvailableDate::firstOrCreate([
            'package_id' => $package2->id,
            'date' => Carbon::now()->addDays(45)->format('Y-m-d'),
        ]);

        // Create test invoices
        $invoice1 = Invoice::firstOrCreate([
            'invoice_number' => 'INV-2025-001',
            'user_id' => $customer->id,
            'package_id' => $package1->id,
            'total' => $package1->price,
            'status' => 'unpaid',
            'available_date_id' => $availableDate1->id,
        ]);

        $invoice2 = Invoice::firstOrCreate([
            'invoice_number' => 'INV-2025-002',
            'user_id' => $customer->id,
            'package_id' => $package2->id,
            'total' => $package2->price,
            'status' => 'waiting',
            'available_date_id' => $availableDate2->id,
            'transaction_id' => 'TXN-123456',
            'transaction_date' => now(),
        ]);

        // Create test emails (booking requests)
        $email1 = Email::firstOrCreate([
            'user_id' => $artist->id,
            'package_id' => $package1->id,
            'invoice_id' => $invoice1->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => '+6281234567890',
            'event_type' => 'Wedding',
            'event_date' => $availableDate1->date,
            'event_location' => 'Jakarta Convention Center',
            'event_description' => 'Wedding ceremony for John and Jane',
            'special_requirements' => 'Need acoustic setup',
            'subject' => 'Wedding Music Booking Request',
            'body' => 'Request for wedding music package',
            'status' => 'waiting',
            'sent_at' => now(),
        ]);

        $email2 = Email::firstOrCreate([
            'user_id' => $artist->id,
            'package_id' => $package2->id,
            'invoice_id' => $invoice2->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => '+6281234567890',
            'event_type' => 'Birthday Party',
            'event_date' => $availableDate2->date,
            'event_location' => 'Grand Ballroom Hotel',
            'event_description' => 'Birthday party for Sarah (25th birthday)',
            'special_requirements' => 'Upbeat and fun music',
            'subject' => 'Birthday Party Music Booking Request',
            'body' => 'Request for birthday party music package',
            'status' => 'approved',
            'sent_at' => now(),
        ]);

        // Create test order (for paid invoice)
        $order = Order::firstOrCreate([
            'invoice_id' => $invoice2->id,
            'user_id' => $customer->id,
            'payment_proof' => 'payments/proof-123456.jpg',
            'status' => 'waiting approval',
        ]);

        $this->command->info('Test booking and invoice data created successfully!');
        $this->command->info('Customer login: customer@test.com / password');
        $this->command->info('Artist login: artist@test.com / password');
    }
}
