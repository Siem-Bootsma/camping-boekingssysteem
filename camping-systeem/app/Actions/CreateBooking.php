<?php

namespace App\Actions;

use App\Mail\BookingConfirmed;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;

class CreateBooking
{
    public function execute(array $data): Booking
    {
        $booking = Booking::create([
            ...$data,
            'status' => Booking::STATUS_CONFIRMED,
        ]);

        Mail::to($booking->guest_email)->send(
            (new BookingConfirmed($booking->load('campingSpot')))->locale(app()->getLocale())
        );

        return $booking;
    }
}
