<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => implode(' ', $this->faker->words(rand(1, 4))),
            'destination' => $this->faker->randomElement(['Yogyakarta', 'Jakarta', 'Surakarta', 'Surabaya', 'Bandung']),
            'departure' => function () {
                $time = $this->faker->time('H:i');
                $timeParts = explode(':', $time);
                $timeParts[0] = rand(3, 23);
                return implode(':', $timeParts);
            },
            'price' => $this->faker->numberBetween(5000, 550000),
            'photo' => $this->faker->randomElement([
                'makam raja imogiri.jpeg',
                'embung potorono.jpg',
                'galaxy waterpark jogja.jpg',
                'gunung mungker.jpg',
                'kampung edukasi watu lumbung.jpg']),
        ];
    }
}
