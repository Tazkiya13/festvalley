<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Artist user IDs yang spesifik
        $artistUserIds = [2, 4, 5, 6]; // ID 2 => Artist, ID 4 => faqih, ID 5 => sultan, ID 6 => luthfi

        // Data spesifik untuk setiap artist
        $artistsData = [
            2 => [
                'stage_name' => 'Artist',
                'real_name' => 'Artist Music',
                'genre' => 'Pop',
                'bio' => 'Professional pop artist with years of experience in live performances and studio recordings.',
            ],
            4 => [
                'stage_name' => 'Faqih',
                'real_name' => 'Faqih Rahman',
                'genre' => 'Jazz',
                'bio' => 'Jazz musician specializing in contemporary and classic jazz performances.',
            ],
            5 => [
                'stage_name' => 'Sultan',
                'real_name' => 'Sultan Music',
                'genre' => 'Rock',
                'bio' => 'Rock artist with powerful vocals and energetic stage presence.',
            ],
            6 => [
                'stage_name' => 'Luthfi',
                'real_name' => 'Luthfi Hakim',
                'genre' => 'Acoustic',
                'bio' => 'Acoustic guitarist and singer-songwriter focusing on melodic and heartfelt music.',
            ],
        ];

        // Buat profile untuk setiap artist
        foreach ($artistUserIds as $userId) {
            $specificData = $artistsData[$userId];

            Profile::factory()->create([
                'user_id' => $userId,
                'stage_name' => $specificData['stage_name'],
                'real_name' => $specificData['real_name'],
                'genre' => $specificData['genre'],
                'bio' => $specificData['bio'],
            ]);
        }
    }
}
