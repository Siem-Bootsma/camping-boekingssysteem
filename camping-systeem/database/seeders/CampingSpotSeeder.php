<?php

namespace Database\Seeders;

use App\Models\CampingSpot;
use Illuminate\Database\Seeder;

class CampingSpotSeeder extends Seeder
{
    public function run(): void
    {
        $accommodations = [
            [
                'name' => 'Tentplaats Bosrand',
                'description' => 'Groene tentplaats aan de rand van het bos met ochtendzon.',
                'capacity' => 3,
                'price_per_night' => 29.50,
                'accommodation_type' => CampingSpot::TYPE_TENT_PITCH,
            ],
            [
                'name' => 'Tentplaats Vuurveld',
                'description' => 'Knusse tentplaats dichtbij het kampvuurveld en sanitair.',
                'capacity' => 2,
                'price_per_night' => 27.50,
                'accommodation_type' => CampingSpot::TYPE_TENT_PITCH,
            ],
            [
                'name' => 'Chalet De Berk',
                'description' => 'Comfortabel chalet met eigen terras en vaste bedden.',
                'capacity' => 4,
                'price_per_night' => 82.50,
                'accommodation_type' => CampingSpot::TYPE_CHALET,
            ],
            [
                'name' => 'Chalet Het Meerzicht',
                'description' => 'Ruim chalet met uitzicht over het water en veel privacy.',
                'capacity' => 5,
                'price_per_night' => 96,
                'accommodation_type' => CampingSpot::TYPE_CHALET,
            ],
            [
                'name' => 'Stacaravan Linde',
                'description' => 'Volledig ingerichte stacaravan met veranda en keuken.',
                'capacity' => 4,
                'price_per_night' => 68,
                'accommodation_type' => CampingSpot::TYPE_STATIC_CARAVAN,
            ],
            [
                'name' => 'Stacaravan Zonnedek',
                'description' => 'Familievriendelijke stacaravan op een rustige plek.',
                'capacity' => 6,
                'price_per_night' => 74.50,
                'accommodation_type' => CampingSpot::TYPE_STATIC_CARAVAN,
            ],
            [
                'name' => 'Kampeerplek Plek A',
                'description' => 'Ruime kampeerplek dichtbij het bos voor caravan of camper.',
                'capacity' => 4,
                'price_per_night' => 37.50,
                'accommodation_type' => CampingSpot::TYPE_CAMPING_PITCH,
            ],
            [
                'name' => 'Kampeerplek Plek B',
                'description' => 'Rustige kampeerplek met uitzicht op het meer.',
                'capacity' => 6,
                'price_per_night' => 45,
                'accommodation_type' => CampingSpot::TYPE_CAMPING_PITCH,
            ],
        ];

        foreach ($accommodations as $accommodation) {
            CampingSpot::updateOrCreate([
                'name' => $accommodation['name'],
            ], [
                ...$accommodation,
                'is_active' => true,
            ]);
        }
    }
}
