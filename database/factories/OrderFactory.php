<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => \App\Models\Invoice::factory(),
            'user_id' => \App\Models\User::factory(),
            'payment_proof' => 'order/' . $this->faker->uuid . '.jpg',
            'status' => $this->faker->randomElement(['waiting approval', 'approved', 'rejected']),
        ];
    }
}
