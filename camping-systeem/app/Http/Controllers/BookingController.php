<?php

namespace App\Http\Controllers;

use App\Actions\CreateBooking;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\CampingSpot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(Request $request): View
    {
        $startDate = $this->dateFilter($request->query('start_date'));
        $endDate = $this->dateFilter($request->query('end_date'));
        $partySize = $request->integer('party_size') > 0 ? $request->integer('party_size') : null;
        $hasDateRange = $startDate && $endDate && $endDate > $startDate;

        $campingSpots = CampingSpot::query()
            ->active()
            ->when($partySize, fn ($query) => $query->where('capacity', '>=', $partySize))
            ->when($hasDateRange, fn ($query) => $query->availableBetween($startDate, $endDate))
            ->orderBy('name')
            ->get();

        return view('bookings.create', [
            'campingSpots' => $campingSpots,
            'hasAvailabilitySearch' => $hasDateRange,
        ]);
    }

    public function store(StoreBookingRequest $request, CreateBooking $createBooking): RedirectResponse
    {
        $booking = $createBooking->execute($request->validated());

        return redirect()->route('bookings.show', $booking);
    }

    public function show(Booking $booking): View
    {
        return view('bookings.show', [
            'booking' => $booking->load('campingSpot'),
        ]);
    }

    private function dateFilter(mixed $value): ?string
    {
        if (! is_string($value) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) !== 1) {
            return null;
        }

        return $value;
    }
}
