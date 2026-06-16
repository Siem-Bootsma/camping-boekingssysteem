<x-mail::message>
@php
    $stayNights = (int) $booking->start_date->diffInDays($booking->end_date);
    $pricePerNight = (float) $booking->campingSpot->price_per_night;
    $estimatedTotal = $stayNights * $pricePerNight;
    $cancellationSubject = __('Cancellation request for booking #:id', ['id' => $booking->id]);
    $cancellationBody = __('Please cancel booking #:id for :name from :start to :end.', [
        'id' => $booking->id,
        'name' => $booking->guest_name,
        'start' => $booking->start_date->toDateString(),
        'end' => $booking->end_date->toDateString(),
    ]);
    $cancellationUrl = 'mailto:'.config('mail.from.address').'?subject='.rawurlencode($cancellationSubject).'&body='.rawurlencode($cancellationBody);
@endphp

# {{ __('Booking confirmed') }}

{{ __('Hello :name, your booking has been confirmed.', ['name' => $booking->guest_name]) }}

{{ __('Below you will find the details of your reservation.') }}

<x-mail::panel>
**{{ __('Booking number') }} #{{ $booking->id }}**

{{ $booking->campingSpot->name }}

{{ __(':spot from :start to :end.', [
    'spot' => $booking->campingSpot->name,
    'start' => $booking->start_date->toDateString(),
    'end' => $booking->end_date->toDateString(),
]) }}

**{{ __('Total') }}:** {{ Illuminate\Support\Number::currency($estimatedTotal, in: 'EUR', locale: app()->getLocale()) }}
</x-mail::panel>

## {{ __('Reservation details') }}

<x-mail::table>
| {{ __('Detail') }} | {{ __('Information') }} |
| :--- | :--- |
| {{ __('Camping spot') }} | {{ $booking->campingSpot->name }} |
| {{ __('Arrival') }} | {{ $booking->start_date->toDateString() }} |
| {{ __('Departure') }} | {{ $booking->end_date->toDateString() }} |
| {{ __('Stay') }} | {{ __(':count nights', ['count' => $stayNights]) }} |
| {{ __('Guests') }} | {{ $booking->party_size }} |
| {{ __('Price per night') }} | {{ Illuminate\Support\Number::currency($pricePerNight, in: 'EUR', locale: app()->getLocale()) }} |
| {{ __('Total') }} | {{ Illuminate\Support\Number::currency($estimatedTotal, in: 'EUR', locale: app()->getLocale()) }} |
</x-mail::table>

## {{ __('Contact details') }}

<x-mail::table>
| {{ __('Detail') }} | {{ __('Information') }} |
| :--- | :--- |
| {{ __('Name') }} | {{ $booking->guest_name }} |
| {{ __('Email') }} | {{ $booking->guest_email }} |
| {{ __('Phone') }} | {{ $booking->guest_phone ?: __('No phone number') }} |
</x-mail::table>

@if ($booking->notes)
<x-mail::panel>
**{{ __('Note') }}**

{{ $booking->notes }}
</x-mail::panel>
@endif

{{ __('We look forward to welcoming you.') }}

<x-mail::button :url="route('bookings.show', $booking)">
{{ __('View booking') }}
</x-mail::button>

## {{ __('Cancel') }}

{{ __('Need to cancel? Send us a cancellation request using the button below. We will confirm the cancellation by email.') }}

<x-mail::button :url="$cancellationUrl">
{{ __('Request cancellation') }}
</x-mail::button>
</x-mail::message>
