<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::factory(2)->create()->each(function ($invoice) {
            $invoice->orders()->saveMany(Order::factory(2)->make());
        });
    }
}
