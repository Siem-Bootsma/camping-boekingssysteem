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
                'image_path' => 'images/tentplaats1.png',
            ],
            [
                'name' => 'Tentplaats Vuurveld',
                'description' => 'Knusse tentplaats dichtbij het kampvuurveld en sanitair.',
                'capacity' => 2,
                'price_per_night' => 27.50,
                'accommodation_type' => CampingSpot::TYPE_TENT_PITCH,
                'image_path' => 'images/tentplaatsen2.png',
            ],
            [
                'name' => 'Tentplaats Dennenhoek',
                'description' => 'Beschutte tentplaats tussen de dennen met veel schaduw.',
                'capacity' => 4,
                'price_per_night' => 31,
                'accommodation_type' => CampingSpot::TYPE_TENT_PITCH,
                'image_path' => 'images/tentplaatsen3.png',
            ],
            [
                'name' => 'Tentplaats Heideveld',
                'description' => 'Rustige tentplaats aan het open veld met avondzon.',
                'capacity' => 3,
                'price_per_night' => 33.50,
                'accommodation_type' => CampingSpot::TYPE_TENT_PITCH,
                'image_path' => 'images/tentplaats1.png',
            ],
            [
                'name' => 'Tentplaats Waterkant',
                'description' => 'Tentplaats vlak bij het water en de wandelroute.',
                'capacity' => 2,
                'price_per_night' => 34,
                'accommodation_type' => CampingSpot::TYPE_TENT_PITCH,
                'image_path' => 'images/tentplaatsen2.png',
            ],
            [
                'name' => 'Chalet De Berk',
                'description' => 'Comfortabel chalet met eigen terras en vaste bedden.',
                'capacity' => 4,
                'price_per_night' => 82.50,
                'accommodation_type' => CampingSpot::TYPE_CHALET,
                'image_path' => 'images/chalet1.png',
            ],
            [
                'name' => 'Chalet Het Meerzicht',
                'description' => 'Ruim chalet met uitzicht over het water en veel privacy.',
                'capacity' => 5,
                'price_per_night' => 96,
                'accommodation_type' => CampingSpot::TYPE_CHALET,
                'image_path' => 'images/chalet2.png',
            ],
            [
                'name' => 'Chalet De Eik',
                'description' => 'Gezellig chalet met keuken, badkamer en overdekt terras.',
                'capacity' => 4,
                'price_per_night' => 88,
                'accommodation_type' => CampingSpot::TYPE_CHALET,
                'image_path' => 'images/chalet3.png',
            ],
            [
                'name' => 'Chalet Duinzicht',
                'description' => 'Licht chalet met vrij uitzicht richting de duinen.',
                'capacity' => 6,
                'price_per_night' => 109,
                'accommodation_type' => CampingSpot::TYPE_CHALET,
                'image_path' => 'images/chalet1.png',
            ],
            [
                'name' => 'Chalet Boslicht',
                'description' => 'Knus chalet aan een rustige laan met veel privacy.',
                'capacity' => 3,
                'price_per_night' => 79.50,
                'accommodation_type' => CampingSpot::TYPE_CHALET,
                'image_path' => 'images/chalet2.png',
            ],
            [
                'name' => 'Stacaravan Linde',
                'description' => 'Volledig ingerichte stacaravan met veranda en keuken.',
                'capacity' => 4,
                'price_per_night' => 68,
                'accommodation_type' => CampingSpot::TYPE_STATIC_CARAVAN,
                'image_path' => 'images/stacaravan1.png',
            ],
            [
                'name' => 'Stacaravan Zonnedek',
                'description' => 'Familievriendelijke stacaravan op een rustige plek.',
                'capacity' => 6,
                'price_per_night' => 74.50,
                'accommodation_type' => CampingSpot::TYPE_STATIC_CARAVAN,
                'image_path' => 'images/stacaravan2.png',
            ],
            [
                'name' => 'Stacaravan Wilg',
                'description' => 'Praktische stacaravan met twee slaapkamers en eigen terras.',
                'capacity' => 4,
                'price_per_night' => 71,
                'accommodation_type' => CampingSpot::TYPE_STATIC_CARAVAN,
                'image_path' => 'images/stacaravan3.png',
            ],
            [
                'name' => 'Stacaravan Horizon',
                'description' => 'Ruime stacaravan voor gezinnen dichtbij de speeltuin.',
                'capacity' => 6,
                'price_per_night' => 84,
                'accommodation_type' => CampingSpot::TYPE_STATIC_CARAVAN,
                'image_path' => 'images/stacaravan1.png',
            ],
            [
                'name' => 'Stacaravan De Kreek',
                'description' => 'Comfortabele stacaravan op een groene plek bij de kreek.',
                'capacity' => 5,
                'price_per_night' => 78.50,
                'accommodation_type' => CampingSpot::TYPE_STATIC_CARAVAN,
                'image_path' => 'images/stacaravan2.png',
            ],
            [
                'name' => 'Kampeerplek Plek A',
                'description' => 'Ruime kampeerplek dichtbij het bos voor caravan of camper.',
                'capacity' => 4,
                'price_per_night' => 37.50,
                'accommodation_type' => CampingSpot::TYPE_CAMPING_PITCH,
                'image_path' => 'images/kampeerplek.png',
            ],
            [
                'name' => 'Kampeerplek Plek B',
                'description' => 'Rustige kampeerplek met uitzicht op het meer.',
                'capacity' => 6,
                'price_per_night' => 45,
                'accommodation_type' => CampingSpot::TYPE_CAMPING_PITCH,
                'image_path' => 'images/Kampvuur-avond.jpg',
            ],
            [
                'name' => 'Kampeerplek Plek C',
                'description' => 'Brede kampeerplek met stroomaansluiting en grasveld.',
                'capacity' => 5,
                'price_per_night' => 42.50,
                'accommodation_type' => CampingSpot::TYPE_CAMPING_PITCH,
                'image_path' => 'images/kampeerplek.png',
            ],
            [
                'name' => 'Kampeerplek Plek D',
                'description' => 'Zonnige kampeerplek dichtbij sanitair en waterpunt.',
                'capacity' => 4,
                'price_per_night' => 39.50,
                'accommodation_type' => CampingSpot::TYPE_CAMPING_PITCH,
                'image_path' => 'images/Kampvuur-avond.jpg',
            ],
            [
                'name' => 'Kampeerplek Plek E',
                'description' => 'Extra ruime plek voor camper of grote caravan.',
                'capacity' => 6,
                'price_per_night' => 49,
                'accommodation_type' => CampingSpot::TYPE_CAMPING_PITCH,
                'image_path' => 'images/kampeerplek.png',
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
