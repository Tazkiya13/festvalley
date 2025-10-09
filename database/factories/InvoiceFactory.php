<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'package_id' => \App\Models\Package::factory(),
            'invoice_number' => $this->faker->unique()->numerify('INV-#####'),
            'total' => $this->faker->randomFloat(2, 150, 1500),
            'status' => $this->faker->randomElement(['waiting', 'unpaid', 'paid', 'rejected']),
            'available_date_id' => \App\Models\AvailableDate::factory(),
            'transaction_date' => $this->faker->optional()->dateTimeBetween('-30 days', '+30 days'),
            'transaction_id' => $this->faker->optional()->numerify('TRX-######'),
        ];
    }
}
