<?php

namespace Database\Factories;

use App\Models\CampingSpot;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampingSpotFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Plek '.fake()->unique()->bothify('??-##'),
            'description' => fake()->sentence(),
            'capacity' => fake()->numberBetween(2, 6),
            'price_per_night' => fake()->randomFloat(2, 25, 75),
            'accommodation_type' => fake()->randomElement(CampingSpot::TYPES),
            'image_path' => 'images/kampeerplek.png',
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
