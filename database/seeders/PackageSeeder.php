<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\AvailableDate;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::factory(10)->create()->each(function ($package) {
            $package->availableDates()->saveMany(AvailableDate::factory(5)->make());
        });
    }
}
