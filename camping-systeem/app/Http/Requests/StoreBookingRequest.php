<?php

namespace App\Http\Requests;

use App\Models\CampingSpot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'camping_spot_id' => ['required', 'integer', 'exists:camping_spots,id'],
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:50'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'party_size' => ['required', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'camping_spot_id' => __('camping spot'),
            'guest_name' => __('name'),
            'guest_email' => __('email'),
            'guest_phone' => __('phone'),
            'start_date' => __('arrival date'),
            'end_date' => __('departure date'),
            'party_size' => __('number of guests'),
            'notes' => __('note'),
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
            'exists' => __('The selected :attribute is invalid.'),
            'string' => __('The :attribute must be text.'),
            'email' => __('The :attribute must be a valid email address.'),
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

                $campingSpot = CampingSpot::find($this->integer('camping_spot_id'));

                if (! $campingSpot?->is_active) {
                    $validator->errors()->add('camping_spot_id', __('This camping spot is not available.'));

                    return;
                }

                if ($this->integer('party_size') > $campingSpot->capacity) {
                    $validator->errors()->add('party_size', __('The number of guests does not fit on this camping spot.'));

                    return;
                }

                $isAvailable = CampingSpot::query()
                    ->whereKey($campingSpot->getKey())
                    ->availableBetween($this->date('start_date'), $this->date('end_date'))
                    ->exists();

                if (! $isAvailable) {
                    $validator->errors()->add('start_date', __('This camping spot is already booked in this period.'));
                }
            },
        ];
    }
}
