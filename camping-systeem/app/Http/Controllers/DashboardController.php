<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function create(): View
    {
        $bookings = Booking::query()
            ->with('campingSpot')
            ->orderBy('start_date')
            ->orderBy('end_date')
            ->get();

        $customers = $bookings
            ->unique(fn (Booking $booking): string => $booking->guest_email)
            ->sortBy('guest_name')
            ->values();

        return view('dashboard', [
            'bookings' => $bookings,
            'customers' => $customers,
        ]);
    }
}
