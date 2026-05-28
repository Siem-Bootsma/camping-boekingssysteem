<?php

namespace App\Actions;

use App\Models\Booking;

class CreateBooking
{
    /**
     * @param  array{
     *     camping_spot_id: int,
     *     guest_name: string,
     *     guest_email: string,
     *     guest_phone?: string|null,
     *     start_date: string,
     *     end_date: string,
     *     party_size: int,
     *     notes?: string|null
     * }  $data
     */
    public function execute(array $data): Booking
    {
        return Booking::create([
            ...$data,
            'status' => Booking::STATUS_CONFIRMED,
        ]);
    }
}
