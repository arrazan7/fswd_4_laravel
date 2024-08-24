<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use DateTime;
use DateInterval;

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
        $travel = Travel::factory()->create();
        $ticket = $this->faker->numberBetween(1, 30); // Menyimpan jumlah tiket

        $faker = Faker::create();

        $randomDate = function () {
            $startDate = '2024-01-01';
            $endDate = '2024-12-31';

            $start = new DateTime($startDate);
            $end = new DateTime($endDate);

            $interval = $end->diff($start);
            $days = $interval->days;

            $randomDays = rand(0, $days);
            $date = $start->add(new DateInterval("P{$randomDays}D"));

            return $date->format('Y-m-d');
        };

        return [
            'user_id' => User::factory()->create()->id,
            'travel_id' => $travel->id,
            'ticket' => $ticket,
            'date' => $randomDate(),
            'price' => $travel->price * $ticket, // Menggunakan variabel $ticket
        ];
    }
}
