<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => env('ADMIN_EMAIL', 'vuurvlieg@gmail.com'),
        ], [
            'name' => 'Beheerder',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin123')),
        ]);
    }
}
