<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeAccommodatieSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'naam' => 'Kampeerplekken',
                'slug' => 'kampeerplekken',
                'omschrijving' => 'Ruime plekken voor caravan, camper of vouwwagen.',
            ],
            [
                'naam' => 'Chalets',
                'slug' => 'chalets',
                'omschrijving' => 'Comfortabele accommodaties met vaste bedden en eigen terras.',
            ],
            [
                'naam' => 'Stacaravans',
                'slug' => 'stacaravans',
                'omschrijving' => 'Volledig ingerichte stacaravans voor gezinnen en langere verblijven.',
            ],
            [
                'naam' => 'Tentplaatsen',
                'slug' => 'tentplaatsen',
                'omschrijving' => 'Groene plaatsen voor tenten dichtbij natuur en voorzieningen.',
            ],
        ];

        foreach ($types as $type) {
            DB::table('type_accommodatie')->updateOrInsert(
                ['slug' => $type['slug']],
                $type,
            );
        }
    }
}
