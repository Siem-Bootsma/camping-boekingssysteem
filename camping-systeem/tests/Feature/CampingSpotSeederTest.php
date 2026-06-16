<?php

use App\Models\CampingSpot;
use Database\Seeders\CampingSpotSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('camping spot seeder creates enough spots for every accommodation type', function () {
    $this->seed(CampingSpotSeeder::class);

    expect(CampingSpot::query()->count())->toBe(20);

    foreach (CampingSpot::TYPES as $type) {
        expect(CampingSpot::query()->where('accommodation_type', $type)->count())->toBe(5);
    }

    CampingSpot::query()
        ->pluck('image_path')
        ->each(function (?string $imagePath): void {
            expect($imagePath)->not->toBeNull()
                ->and(public_path($imagePath))->toBeFile();
        });

    expect(CampingSpot::query()->pluck('image_path')->all())->toContain(
        'images/chalet4.png',
        'images/kampeerplek2.png',
    );

    expect(CampingSpot::query()->pluck('name')->all())->toEqualCanonicalizing([
        'Tentplaats Bosrand',
        'Tentplaats Vuurveld',
        'Tentplaats Dennenhoek',
        'Tentplaats Heideveld',
        'Tentplaats Waterkant',
        'Chalet De Berk',
        'Chalet Het Meerzicht',
        'Chalet De Eik',
        'Chalet Duinzicht',
        'Chalet Boslicht',
        'Stacaravan Linde',
        'Stacaravan Zonnedek',
        'Stacaravan Wilg',
        'Stacaravan Horizon',
        'Stacaravan De Kreek',
        'Kampeerplek Plek A',
        'Kampeerplek Plek B',
        'Kampeerplek Plek C',
        'Kampeerplek Plek D',
        'Kampeerplek Plek E',
    ]);
});
