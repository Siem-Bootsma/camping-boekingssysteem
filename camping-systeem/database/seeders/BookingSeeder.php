<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\CampingSpot;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{

    public function run(): void
    {
        CampingSpot::query()->each(function (CampingSpot $campingSpot): void {
            Booking::factory()->for($campingSpot)->create();
        });
    }
}
