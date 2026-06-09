<?php

namespace App\Http\Controllers;

use App\Actions\CreateBooking;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\CampingSpot;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
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
        $accommodationTypes = $this->allowedArray($request->array('accommodation_types'), CampingSpot::TYPES);
        $priceRanges = $this->allowedArray($request->array('price_ranges'), ['budget', 'standard', 'premium']);
        $capacityRanges = $this->allowedArray($request->array('capacity_ranges'), ['small', 'medium', 'large']);
        $hasDateRange = $startDate && $endDate && $endDate > $startDate;
        $stayNights = $hasDateRange
            ? (int) CarbonImmutable::parse($startDate)->diffInDays(CarbonImmutable::parse($endDate))
            : null;

        $campingSpots = CampingSpot::query()
            ->active()
            ->when($partySize, fn ($query) => $query->where('capacity', '>=', $partySize))
            ->when($accommodationTypes !== [], fn (Builder $query) => $query->whereIn('accommodation_type', $accommodationTypes))
            ->when($priceRanges !== [], fn (Builder $query) => $this->applyPriceRanges($query, $priceRanges))
            ->when($capacityRanges !== [], fn (Builder $query) => $this->applyCapacityRanges($query, $capacityRanges))
            ->when($hasDateRange, fn ($query) => $query->availableBetween($startDate, $endDate))
            ->orderBy('price_per_night')
            ->orderBy('name')
            ->get();

        return view('bookings.create', [
            'campingSpots' => $campingSpots,
            'hasAvailabilitySearch' => $hasDateRange,
            'selectedAccommodationTypes' => $accommodationTypes,
            'selectedCapacityRanges' => $capacityRanges,
            'selectedPriceRanges' => $priceRanges,
            'stayNights' => $stayNights,
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

    /**
     * @param  list<string>  $values
     * @param  list<string>  $allowed
     * @return list<string>
     */
    private function allowedArray(array $values, array $allowed): array
    {
        return array_values(array_intersect($values, $allowed));
    }

    /**
     * @param  list<string>  $priceRanges
     */
    private function applyPriceRanges(Builder $query, array $priceRanges): Builder
    {
        return $query->where(function (Builder $query) use ($priceRanges): void {
            foreach ($priceRanges as $priceRange) {
                match ($priceRange) {
                    'budget' => $query->orWhere('price_per_night', '<', 40),
                    'standard' => $query->orWhereBetween('price_per_night', [40, 60]),
                    'premium' => $query->orWhere('price_per_night', '>', 60),
                };
            }
        });
    }

    /**
     * @param  list<string>  $capacityRanges
     */
    private function applyCapacityRanges(Builder $query, array $capacityRanges): Builder
    {
        return $query->where(function (Builder $query) use ($capacityRanges): void {
            foreach ($capacityRanges as $capacityRange) {
                match ($capacityRange) {
                    'small' => $query->orWhereBetween('capacity', [1, 3]),
                    'medium' => $query->orWhereBetween('capacity', [4, 5]),
                    'large' => $query->orWhere('capacity', '>=', 6),
                };
            }
        });
    }
}
