<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $packageTypes = [
            'Solo Artist' => [150, 300],
            'Duo Performance' => [250, 450],
            'Band (3-4 members)' => [400, 800],
            'Orchestra' => [800, 1500],
            'DJ Set' => [200, 500],
            'Jazz Trio' => [300, 600],
            'Rock Band' => [450, 900],
            'Acoustic Set' => [180, 350]
        ];

        $packageName = $this->faker->randomElement(array_keys($packageTypes));
        $priceRange = $packageTypes[$packageName];

        return [
            'user_id' => \App\Models\User::factory(),
            'package_name' => $packageName,
            'description' => $this->faker->paragraph(),
            'image' => 'https://picsum.photos/640/480?random=' . $this->faker->unique()->numberBetween(1000, 9999),
            'price' => $this->faker->randomFloat(2, $priceRange[0], $priceRange[1]), // Realistic EUR prices for artist packages
        ];
    }
}
