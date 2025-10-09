<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Email;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\AvailableDate;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerDummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the customer user
        $customer = User::where('email', 'customer@gmail.com')->first();
        
        if (!$customer) {
            $this->command->error('Customer user not found. Please run UserSeeder first.');
            return;
        }

        // Get some random packages and available dates
        $packages = Package::inRandomOrder()->take(5)->get();
        $availableDates = AvailableDate::inRandomOrder()->take(5)->get();

        if ($packages->isEmpty() || $availableDates->isEmpty()) {
            $this->command->error('No packages or available dates found. Please run PackageSeeder first.');
            return;
        }

        // Create 3 invoices for the customer
        foreach (range(1, 3) as $index) {
            $package = $packages->random();
            $availableDate = $availableDates->random();
            
            $invoice = Invoice::create([
                'user_id' => $customer->id,
                'package_id' => $package->id,
                'invoice_number' => 'INV-CUST-' . time() . '-' . str_pad($index, 3, '0', STR_PAD_LEFT),
                'total' => fake()->randomFloat(2, 500, 2000),
                'status' => fake()->randomElement(['waiting', 'unpaid', 'paid']),
                'available_date_id' => $availableDate->id,
                'transaction_date' => fake()->dateTimeBetween('-60 days', '+30 days'),
                'transaction_id' => 'TRX-' . fake()->numerify('######'),
            ]);

            // Create an order for each invoice
            Order::create([
                'invoice_id' => $invoice->id,
                'user_id' => $customer->id,
                'payment_proof' => 'order/customer_payment_' . $index . '.jpg',
                'status' => fake()->randomElement(['waiting approval', 'approved', 'rejected']),
            ]);

            // Create an email record for the booking
            Email::create([
                'invoice_id' => $invoice->id,
                'user_id' => $customer->id,
                'package_id' => $package->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => fake()->phoneNumber(),
                'event_type' => 'Performance',
                'event_date' => $availableDate->date,
                'event_location' => fake()->city(),
                'event_description' => 'Booking for ' . $package->name,
                'special_requirements' => fake()->optional()->sentence(),
                'subject' => 'Booking Confirmation - ' . $package->name,
                'body' => "Dear {$customer->name},\n\nThank you for booking {$package->name}. Your booking has been received and is being processed.\n\nBooking Details:\n- Package: {$package->name}\n- Date: {$availableDate->date}\n- Total: \${$invoice->total}\n\nBest regards,\nFest Valley Team",
                'status' => 'approved',
                'sent_at' => now(),
            ]);

            $this->command->info("Created invoice #{$invoice->invoice_number} for customer with order and email");
        }

        // Create additional invoices with different statuses for testing
        $statusExamples = [
            ['status' => 'paid', 'order_status' => 'approved'],
            ['status' => 'unpaid', 'order_status' => 'waiting approval'],
            ['status' => 'rejected', 'order_status' => 'rejected'],
        ];

        foreach ($statusExamples as $index => $example) {
            $package = $packages->random();
            $availableDate = $availableDates->random();
            
            $invoice = Invoice::create([
                'user_id' => $customer->id,
                'package_id' => $package->id,
                'invoice_number' => 'INV-DEMO-' . time() . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'total' => fake()->randomFloat(2, 300, 1500),
                'status' => $example['status'],
                'available_date_id' => $availableDate->id,
                'transaction_date' => $example['status'] === 'paid' ? fake()->dateTimeBetween('-30 days', 'now') : null,
                'transaction_id' => $example['status'] === 'paid' ? 'TRX-' . fake()->numerify('######') : null,
            ]);

            Order::create([
                'invoice_id' => $invoice->id,
                'user_id' => $customer->id,
                'payment_proof' => $example['order_status'] !== 'waiting approval' ? 'order/demo_payment_' . ($index + 1) . '.jpg' : null,
                'status' => $example['order_status'],
            ]);

            Email::create([
                'invoice_id' => $invoice->id,
                'user_id' => $customer->id,
                'package_id' => $package->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => fake()->phoneNumber(),
                'event_type' => 'Performance',
                'event_date' => $availableDate->date,
                'event_location' => fake()->city(),
                'event_description' => 'Demo booking for ' . $package->name,
                'special_requirements' => fake()->optional()->sentence(),
                'subject' => 'Booking Update - ' . $package->name,
                'body' => "Dear {$customer->name},\n\nYour booking for {$package->name} has been updated.\n\nStatus: " . ucfirst($example['status']) . "\n\nBest regards,\nFest Valley Team",
                'status' => $example['status'] === 'paid' ? 'approved' : ($example['status'] === 'rejected' ? 'rejected' : 'waiting'),
                'sent_at' => now(),
            ]);

            $this->command->info("Created demo invoice #{$invoice->invoice_number} with status: {$example['status']}");
        }

        $this->command->info('✅ Successfully created dummy booking and invoice data for customer@gmail.com');
        $this->command->info('📊 Summary:');
        $this->command->info("   - Total Invoices: " . Invoice::where('user_id', $customer->id)->count());
        $this->command->info("   - Total Orders: " . Order::where('user_id', $customer->id)->count());
        $this->command->info("   - Total Emails: " . Email::whereIn('invoice_id', Invoice::where('user_id', $customer->id)->pluck('id'))->count());
    }
}
