<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genres = ['Pop', 'Rock', 'Jazz', 'Classical', 'Blues', 'Country', 'Electronic', 'Acoustic', 'Folk', 'Reggae'];
        $cities = ['Paris', 'Berlin', 'Rome', 'Madrid', 'Amsterdam', 'Vienna', 'Prague', 'Barcelona', 'Munich', 'Brussels', 'Hamburg', 'Milan', 'Lyon', 'Cologne', 'Naples'];
        $provinces = ['Île-de-France', 'Bavaria', 'Lazio', 'Catalonia', 'North Holland', 'Vienna', 'Prague', 'Andalusia', 'Lombardy', 'Brussels-Capital', 'North Rhine-Westphalia', 'Tuscany', 'Auvergne-Rhône-Alpes', 'Baden-Württemberg', 'Campania'];

        $languages = ['English', 'German', 'French', 'Spanish', 'Italian', 'Dutch', 'Portuguese', 'Polish', 'Czech', 'Swedish'];
        $equipment = ['Guitar', 'Microphone', 'Keyboard', 'Drums', 'Bass', 'Sound System', 'Violin', 'Piano', 'Amplifier', 'Mixer'];

        return [
            'stage_name' => $this->faker->name() . ' Music',
            'real_name' => $this->faker->name(),
            'bio' => $this->faker->paragraph(3),
            'genre' => $this->faker->randomElement($genres),
            'address' => $this->faker->address(),
            'city' => $this->faker->randomElement($cities),
            'province' => $this->faker->randomElement($provinces),
            'postal_code' => $this->faker->postcode(),
            'maps_link' => 'https://maps.google.com/?q=' . urlencode($this->faker->address()),
            'phone' => $this->faker->phoneNumber(),
            'social_media' => [
                'instagram' => 'https://instagram.com/' . $this->faker->userName(),
                'facebook' => 'https://facebook.com/' . $this->faker->userName(),
                'youtube' => 'https://youtube.com/channel/' . $this->faker->uuid(),
                'tiktok' => 'https://tiktok.com/@' . $this->faker->userName(),
            ],
            'profile_photo' => 'https://picsum.photos/400/400?random=' . $this->faker->unique()->numberBetween(5000, 8999),
            'cover_photo' => 'https://picsum.photos/1200/400?random=' . $this->faker->unique()->numberBetween(9000, 9999),
            'years_experience' => $this->faker->numberBetween(1, 20),
            'languages' => $this->faker->randomElements($languages, $this->faker->numberBetween(1, 3)),
            'equipment_owned' => $this->faker->randomElements($equipment, $this->faker->numberBetween(2, 6)),
        ];
    }
}
