<?php

namespace App\Actions;

use App\Models\Booking;

class CreateBooking
{
    public function execute(array $data): Booking
    {
        return Booking::create([
            ...$data,
            'status' => Booking::STATUS_CONFIRMED,
        ]);
    }
}
