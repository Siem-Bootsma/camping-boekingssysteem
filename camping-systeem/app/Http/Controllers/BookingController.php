<?php

namespace App\Http\Controllers;

use App\Actions\CreateBooking;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\CampingSpot;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
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
        $calendarMonth = $this->monthFilter($request->query('month'))
            ?? CarbonImmutable::parse($startDate ?? now())->startOfMonth();
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
            'availabilityCalendar' => $this->availabilityCalendar($request, $campingSpot, $calendarMonth),
            'calendarMonth' => $calendarMonth,
            'campingSpot' => $campingSpot,
            'hasAvailabilitySearch' => $hasDateRange,
            'isAvailable' => $isAvailable,
            'spotReview' => $this->spotReview($campingSpot),
            'stayNights' => $stayNights,
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load('campingSpot');
        $stayNights = (int) $booking->start_date->diffInDays($booking->end_date);
        $estimatedTotal = $stayNights * (float) $booking->campingSpot->price_per_night;

        return view('bookings.show', [
            'booking' => $booking,
            'estimatedTotal' => $estimatedTotal,
            'stayNights' => $stayNights,
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
     * @return array{
     *     score: string,
     *     review_count: string,
     *     quote: string,
     *     reviewer: string,
     *     country: string,
     *     initial: string,
     *     best_score: string
     * }
     */
    private function spotReview(CampingSpot $campingSpot): array
    {
        $reviews = [
            [
                'score' => '8,4',
                'review_count' => '1.538',
                'quote' => __('Clean, quiet and friendly campsite with plenty of space for a relaxed stay.'),
                'reviewer' => 'Jan',
                'country' => 'Nederland',
                'initial' => 'J',
                'best_score' => '8,8',
            ],
            [
                'score' => '9,1',
                'review_count' => '842',
                'quote' => 'Heerlijk rustige plek met veel groen en genoeg privacy voor het hele gezin.',
                'reviewer' => 'Sanne',
                'country' => 'Nederland',
                'initial' => 'S',
                'best_score' => '9,3',
            ],
            [
                'score' => '8,7',
                'review_count' => '1.126',
                'quote' => 'Mooie ruime plaats, fijne sfeer en de voorzieningen waren dichtbij.',
                'reviewer' => 'Mark',
                'country' => 'Belgie',
                'initial' => 'M',
                'best_score' => '9,0',
            ],
            [
                'score' => '9,4',
                'review_count' => '674',
                'quote' => 'Alles was netjes verzorgd en de plek lag perfect voor een ontspannen weekend.',
                'reviewer' => 'Lisa',
                'country' => 'Duitsland',
                'initial' => 'L',
                'best_score' => '9,5',
            ],
            [
                'score' => '8,9',
                'review_count' => '963',
                'quote' => 'Gezellige camping, schoon sanitair en genoeg ruimte rond de caravan.',
                'reviewer' => 'Thomas',
                'country' => 'Nederland',
                'initial' => 'T',
                'best_score' => '9,2',
            ],
        ];

        return $reviews[crc32($campingSpot->name) % count($reviews)];
    }

    private function monthFilter(mixed $value): ?CarbonImmutable
    {
        if (! is_string($value) || preg_match('/^\d{4}-\d{2}$/', $value) !== 1) {
            return null;
        }

        return CarbonImmutable::createFromFormat('Y-m-d', $value.'-01')->startOfMonth();
    }

    /**
     * @return array{
     *     months: list<array{
     *         label: string,
     *         days: list<array{
     *             date: CarbonImmutable,
     *             isCurrentMonth: bool,
     *             isPast: bool,
     *             isBooked: bool,
     *             isSelectedStart: bool,
     *             isSelectedEnd: bool,
     *             isInSelectedRange: bool,
     *             selectsEndDate: bool,
     *             selectUrl: string|null
     *         }>
     *     }>,
     *     nextMonthUrl: string,
     *     previousMonthUrl: string
     * }
     */
    private function availabilityCalendar(Request $request, CampingSpot $campingSpot, CarbonImmutable $calendarMonth): array
    {
        $displayMonths = [
            $calendarMonth->startOfMonth(),
            $calendarMonth->addMonth()->startOfMonth(),
        ];
        $gridStart = $displayMonths[0]->startOfWeek(CarbonInterface::MONDAY);
        $gridEnd = $displayMonths[1]->endOfMonth()->endOfWeek(CarbonInterface::SUNDAY);
        $today = CarbonImmutable::today();
        $selectedStart = $this->dateFilter($request->query('start_date'));
        $selectedEnd = $this->dateFilter($request->query('end_date'));
        $selectedStartDate = $selectedStart ? CarbonImmutable::parse($selectedStart) : null;
        $selectedEndDate = $selectedEnd ? CarbonImmutable::parse($selectedEnd) : null;

        $bookings = $campingSpot->bookings()
            ->where('status', Booking::STATUS_CONFIRMED)
            ->overlapping($gridStart, $gridEnd->addDay())
            ->get(['start_date', 'end_date']);

        $rangeContainsBooking = function (CarbonImmutable $rangeStart, CarbonImmutable $rangeEnd) use ($bookings): bool {
            return $rangeEnd->greaterThan($rangeStart)
                && $bookings->contains(function (Booking $booking) use ($rangeStart, $rangeEnd): bool {
                    return $rangeStart->lessThan($booking->end_date)
                        && $rangeEnd->greaterThan($booking->start_date);
                });
        };

        $months = [];

        foreach ($displayMonths as $displayMonth) {
            $monthStart = $displayMonth->startOfMonth();
            $monthEnd = $displayMonth->endOfMonth();
            $monthGridStart = $monthStart->startOfWeek(CarbonInterface::MONDAY);
            $monthGridEnd = $monthEnd->endOfWeek(CarbonInterface::SUNDAY);
            $days = [];

            for ($date = $monthGridStart; $date->lessThanOrEqualTo($monthGridEnd); $date = $date->addDay()) {
                $dateString = $date->toDateString();
                $isBooked = $bookings->contains(function (Booking $booking) use ($date): bool {
                    return $date->greaterThanOrEqualTo($booking->start_date)
                        && $date->lessThan($booking->end_date);
                });
                $isPast = $date->lessThan($today);
                $isCurrentMonth = $date->isSameMonth($monthStart);
                $isInSelectedRange = $selectedStartDate
                    && $selectedEndDate
                    && $date->betweenIncluded($selectedStartDate, $selectedEndDate);
                $selectParameters = [
                    'campingSpot' => $campingSpot,
                    'start_date' => $dateString,
                ];
                $selectsEndDate = false;

                if (
                    $selectedStartDate
                    && ! $selectedEndDate
                    && $date->greaterThan($selectedStartDate)
                    && ! $rangeContainsBooking($selectedStartDate, $date)
                ) {
                    $selectsEndDate = true;
                    $selectParameters['start_date'] = $selectedStartDate->toDateString();
                    $selectParameters['end_date'] = $dateString;
                }

                $days[] = [
                    'date' => $date,
                    'isCurrentMonth' => $isCurrentMonth,
                    'isPast' => $isPast,
                    'isBooked' => $isBooked,
                    'isSelectedStart' => $selectedStart === $dateString,
                    'isSelectedEnd' => $selectedEnd === $dateString,
                    'isInSelectedRange' => $isInSelectedRange,
                    'selectsEndDate' => $selectsEndDate,
                    'selectUrl' => (! $isPast && ! $isBooked && $isCurrentMonth)
                        ? route('bookings.spots.show', array_merge(
                            $selectParameters,
                            $request->only(['party_size', 'accommodation_types', 'min_price', 'max_price', 'capacity_ranges'])
                        )).'#availability-calendar'
                        : null,
                ];
            }

            $months[] = [
                'label' => $displayMonth->format('F Y'),
                'days' => $days,
            ];
        }

        return [
            'months' => $months,
            'nextMonthUrl' => route('bookings.spots.show', array_merge([
                'campingSpot' => $campingSpot,
                'month' => $calendarMonth->addMonth()->format('Y-m'),
            ], $request->only(['start_date', 'end_date', 'party_size', 'accommodation_types', 'min_price', 'max_price', 'capacity_ranges']))).'#availability-calendar',
            'previousMonthUrl' => route('bookings.spots.show', array_merge([
                'campingSpot' => $campingSpot,
                'month' => $calendarMonth->subMonth()->format('Y-m'),
            ], $request->only(['start_date', 'end_date', 'party_size', 'accommodation_types', 'min_price', 'max_price', 'capacity_ranges']))).'#availability-calendar',
        ];
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
