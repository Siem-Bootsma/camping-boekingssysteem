<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDashboardBookingRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
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

    public function update(UpdateDashboardBookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());

        return redirect()
            ->route('dashboard', ['edit_booking' => $booking])
            ->with('dashboard_status', __('Reservation updated.'));
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()
            ->route('dashboard')
            ->with('dashboard_status', __('Reservation cancelled.'));
    }
}
