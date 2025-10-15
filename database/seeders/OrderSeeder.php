<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()->count(2)->create(['status' => 'approved']);
        Order::factory()->count(2)->create(['status' => 'waiting approval']);
        Order::factory()->count(2)->create(['status' => 'rejected']);
    }
}
