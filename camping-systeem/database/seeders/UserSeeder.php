<?php

namespace Database\Seeders;

use App\Models\Beheerder;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $beheerders = [
            [
                'e-mailadres' => 'vuurvlieg@gmail.nl',
                'wachtwoord' => '123456',
            ],
        ];
        foreach ($beheerders as $beheerder) {
            Beheerder::create([
                'e-mailadres' => $beheerder['e-mailadres'],
                'wachtwoord' => $beheerder['wachtwoord'],
            ]);
        }
    }
}
