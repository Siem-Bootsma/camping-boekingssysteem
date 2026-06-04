<?php

namespace Database\Seeders;

use App\Models\Beheerder;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
public function run(): void
{

    $user = [
                [
        'email' => 'vuurvlieg@gmail.com',
        'password' => 'Admin123',
],
];
foreach ($user as $user) {
        Beheerder::create([
            'email' => $user['email'],
            'password' => $user['password'],
         ]);
        }
    }
}
