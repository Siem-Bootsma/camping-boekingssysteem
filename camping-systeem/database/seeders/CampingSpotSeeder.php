<?php

namespace Database\Seeders;

use App\Models\CampingSpot;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CampingSpotSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        CampingSpot::factory()
            ->count(10)
            ->sequence(fn (Sequence $sequence) => [
                'name' => 'Kampeerplek '.($sequence->index + 1),
                'capacity' => fake()->numberBetween(2, 6),
            ])
            ->create();
    }
}
