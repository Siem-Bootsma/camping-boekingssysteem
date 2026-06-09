<?php

namespace Database\Seeders;

use App\Models\CampingSpot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialSetupSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $adminEmail = env('ADMIN_EMAIL', 'vuurvlieg@gmail.com');
        $adminPassword = env('ADMIN_PASSWORD', 'Admin123');

        User::updateOrCreate([
            'email' => $adminEmail,
        ], [
            'name' => 'Beheerder',
            'password' => Hash::make($adminPassword),
        ]);

        // Create some sample camping spots
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
