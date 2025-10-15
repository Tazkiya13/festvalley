<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('asdasd'),
                'email_verified_at' => now(),
            ]
        );

        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        $artist = User::updateOrCreate(
            ['email' => 'artist@gmail.com'],
            [
                'name' => 'Artist',
                'password' => bcrypt('asdasd'),
                'email_verified_at' => now(),
            ]
        );

        if (!$artist->hasRole('Artist')) {
            $artist->assignRole('Artist');
        }

        $customer = User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Customer',
                'password' => bcrypt('asdasd'),
                'email_verified_at' => now(),
            ]
        );

        if (!$customer->hasRole('Customer')) {
            $customer->assignRole('Customer');
        }

        $faqih = User::updateOrCreate(
            ['email' => 'faqih@gmail.com'],
            [
                'name' => 'Faqih',
                'password' => bcrypt('asdasd'),
                'email_verified_at' => now(),
            ]
        );

        if (!$faqih->hasRole('Artist')) {
            $faqih->assignRole('Artist');
        }

        $sultan = User::updateOrCreate(
            ['email' => 'sultan@gmail.com'],
            [
                'name' => 'Sultan',
                'password' => bcrypt('asdasd'),
                'email_verified_at' => now(),
            ]
        );

        if (!$sultan->hasRole('Artist')) {
            $sultan->assignRole('Artist');
        }

        $luthfi = User::updateOrCreate(
            ['email' => 'luthfi@gmail.com'],
            [
                'name' => 'Luthfi',
                'password' => bcrypt('asdasd'),
                'email_verified_at' => now(),
            ]
        );

        if (!$luthfi->hasRole('Artist')) {
            $luthfi->assignRole('Artist');
        }

        $luluk = User::updateOrCreate(
            ['email' => 'luluk@gmail.com'],
            [
                'name' => 'Luluk',
                'password' => bcrypt('asdasd'),
                'email_verified_at' => now(),
            ]
        );

        if (!$luluk->hasRole('Customer')) {
            $luluk->assignRole('Customer');
        }
    }
}
