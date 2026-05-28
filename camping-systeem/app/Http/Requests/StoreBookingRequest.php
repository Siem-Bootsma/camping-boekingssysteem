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

    /**
     * @return array<string, list<string>>
     */
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
                    $validator->errors()->add('camping_spot_id', 'Deze kampeerplek is niet beschikbaar.');

                    return;
                }

                if ($this->integer('party_size') > $campingSpot->capacity) {
                    $validator->errors()->add('party_size', 'Het aantal gasten past niet op deze kampeerplek.');

                    return;
                }

                $isAvailable = CampingSpot::query()
                    ->whereKey($campingSpot->getKey())
                    ->availableBetween($this->date('start_date'), $this->date('end_date'))
                    ->exists();

                if (! $isAvailable) {
                    $validator->errors()->add('start_date', 'Deze kampeerplek is al geboekt in deze periode.');
                }
            },
        ];
    }
}
