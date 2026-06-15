<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateDashboardBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'party_size' => ['required', 'integer', 'min:1', 'max:20'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'start_date' => __('arrival date'),
            'end_date' => __('departure date'),
            'party_size' => __('number of guests'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => __('The :attribute field is required.'),
            'integer' => __('The :attribute must be a number.'),
            'date' => __('The :attribute must be a valid date.'),
            'after_or_equal' => __('The :attribute must be today or later.'),
            'after' => __('The :attribute must be after :date.'),
            'min' => __('The :attribute must be at least :min.'),
            'max' => __('The :attribute may not be greater than :max.'),
        ];
    }

    /**
     * @return list<callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $booking = $this->route('booking');

                if (! $booking instanceof Booking) {
                    return;
                }

                $booking->loadMissing('campingSpot');
                $campingSpot = $booking->campingSpot;

                if (! $campingSpot?->is_active) {
                    $validator->errors()->add('start_date', __('This camping spot is not available.'));

                    return;
                }

                if ($this->integer('party_size') > $campingSpot->capacity) {
                    $validator->errors()->add('party_size', __('The number of guests does not fit on this camping spot.'));

                    return;
                }

                $hasOverlappingBooking = Booking::query()
                    ->whereBelongsTo($campingSpot)
                    ->whereKeyNot($booking->getKey())
                    ->where('status', Booking::STATUS_CONFIRMED)
                    ->overlapping($this->date('start_date'), $this->date('end_date'))
                    ->exists();

                if ($hasOverlappingBooking) {
                    $validator->errors()->add('start_date', __('This camping spot is already booked in this period.'));
                }
            },
        ];
    }
}
