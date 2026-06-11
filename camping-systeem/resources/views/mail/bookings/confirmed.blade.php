<x-mail::message>
# {{ __('Booking confirmed') }}

{{ __('Hello :name, your booking has been confirmed.', ['name' => $booking->guest_name]) }}

<x-mail::panel>
{{ __('Camping spot') }}: {{ $booking->campingSpot->name }}

{{ __('Arrival') }}: {{ $booking->start_date->toDateString() }}

{{ __('Departure') }}: {{ $booking->end_date->toDateString() }}

{{ __('Guests') }}: {{ $booking->party_size }}
</x-mail::panel>

{{ __('We look forward to welcoming you.') }}

<x-mail::button :url="route('bookings.show', $booking)">
{{ __('View booking') }}
</x-mail::button>

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
</x-mail::message>
