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
        [$selectedMinPrice, $selectedMaxPrice] = $this->priceRangeFilter($request);
        $capacityRanges = $this->allowedArray($request->array('capacity_ranges'), ['small', 'medium', 'large']);
        $hasDateRange = $startDate && $endDate && $endDate > $startDate;
        $stayNights = $hasDateRange
            ? (int) CarbonImmutable::parse($startDate)->diffInDays(CarbonImmutable::parse($endDate))
            : null;

        $campingSpots = CampingSpot::query()
            ->active()
            ->when($partySize, fn ($query) => $query->where('capacity', '>=', $partySize))
            ->when($accommodationTypes !== [], fn (Builder $query) => $query->whereIn('accommodation_type', $accommodationTypes))
            ->when($selectedMinPrice > 25, fn (Builder $query) => $query->where('price_per_night', '>=', $selectedMinPrice))
            ->when($selectedMaxPrice < 120, fn (Builder $query) => $query->where('price_per_night', '<=', $selectedMaxPrice))
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
            'selectedMinPrice' => $selectedMinPrice,
            'selectedMaxPrice' => $selectedMaxPrice,
            'stayNights' => $stayNights,
        ]);
    }

    public function store(StoreBookingRequest $request, CreateBooking $createBooking): RedirectResponse
    {
        $booking = $createBooking->execute($request->validated());

        return redirect()->route('bookings.show', $booking);
    }

    public function showSpot(Request $request, CampingSpot $campingSpot): View
    {
        abort_unless($campingSpot->is_active, 404);

        $startDate = $this->dateFilter($request->query('start_date'));
        $endDate = $this->dateFilter($request->query('end_date'));
        $hasDateRange = $startDate && $endDate && $endDate > $startDate;
        $stayNights = $hasDateRange
            ? (int) CarbonImmutable::parse($startDate)->diffInDays(CarbonImmutable::parse($endDate))
            : null;
        $isAvailable = $hasDateRange
            ? CampingSpot::query()
                ->whereKey($campingSpot->getKey())
                ->availableBetween($startDate, $endDate)
                ->exists()
            : null;

        return view('bookings.spot-show', [
            'campingSpot' => $campingSpot,
            'hasAvailabilitySearch' => $hasDateRange,
            'isAvailable' => $isAvailable,
            'stayNights' => $stayNights,
        ]);
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
     * @return array{0: int, 1: int}
     */
    private function priceRangeFilter(Request $request): array
    {
        $minPrice = $request->has('min_price')
            ? min(max($request->integer('min_price'), 25), 120)
            : 25;

        $maxPrice = $request->has('max_price')
            ? min(max($request->integer('max_price'), 25), 120)
            : 120;

        if ($minPrice > $maxPrice) {
            return [$maxPrice, $minPrice];
        }

        return [$minPrice, $maxPrice];
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
