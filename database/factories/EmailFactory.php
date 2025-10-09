<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
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
            'order_id' => null, // Nullable
            'user_id' => \App\Models\User::factory(),
            'package_id' => \App\Models\Package::factory(),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => $this->faker->phoneNumber(),
            'event_type' => $this->faker->randomElement(['Wedding', 'Corporate Event', 'Birthday Party', 'Concert']),
            'event_date' => $this->faker->dateTimeBetween('+1 week', '+3 months')->format('Y-m-d'),
            'event_location' => $this->faker->address(),
            'event_description' => $this->faker->sentence(),
            'special_requirements' => $this->faker->optional()->sentence(),
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['waiting', 'approved', 'rejected']),
            'sent_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
