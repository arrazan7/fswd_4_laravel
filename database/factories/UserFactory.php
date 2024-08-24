<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'fullname' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'password' => bcrypt('rahasia'),
            'role' => 'public',
            'photo' => $this->faker->randomElement([
                'corn_dog.jpeg',
                'donut.png',
                'pukis.jpg',
                'tahu_mercon.png',
                'bakwan_krispi.png']),
        ];
    }
}
