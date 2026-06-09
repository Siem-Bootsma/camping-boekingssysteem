<?php

namespace Database\Seeders;

use App\Models\CampingSpot;
use Illuminate\Database\Seeder;

class CampingSpotSeeder extends Seeder
{
    public function run(): void
    {
        CampingSpot::updateOrCreate([
            'name' => 'Plek A',
        ], [
            'description' => 'Ruime plek dichtbij het bos.',
            'capacity' => 4,
            'price_per_night' => 37.50,
            'is_active' => true,
        ]);

        CampingSpot::updateOrCreate([
            'name' => 'Plek B',
        ], [
            'description' => 'Rustige plek met uitzicht op het meer.',
            'capacity' => 6,
            'price_per_night' => 45,
            'is_active' => true,
        ]);
    }
}
