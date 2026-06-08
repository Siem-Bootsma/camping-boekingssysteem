<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\CampingSpot;
use Illuminate\Database\Eloquent\Factories\Factory;


class BookingFactory extends Factory
{

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+2 months');

        return [
            'camping_spot_id' => CampingSpot::factory(),
            'guest_name' => fake()->name(),
            'guest_email' => fake()->safeEmail(),
            'guest_phone' => fake()->optional()->phoneNumber(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $startDate->modify('+3 days')->format('Y-m-d'),
            'party_size' => fake()->numberBetween(1, 4),
            'status' => Booking::STATUS_CONFIRMED,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
