<?php

use Database\Seeders\TypeAccommodatieSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('type accommodatie seeder creates the accommodation types', function () {
    $this->seed(TypeAccommodatieSeeder::class);

    expect(DB::table('type_accommodatie')->pluck('naam')->all())->toEqualCanonicalizing([
        'Kampeerplekken',
        'Chalets',
        'Stacaravans',
        'Tentplaatsen',
    ]);
});
