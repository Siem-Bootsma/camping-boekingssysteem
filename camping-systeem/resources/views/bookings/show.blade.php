<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Booking confirmed') }}</title>

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite('resources/css/app.css')
        @endif
    </head>
    <body class="min-h-screen bg-[#f3ead8] text-[#213126] antialiased">
        <main class="grid min-h-screen place-items-center bg-[radial-gradient(circle_at_20%_20%,#f8c76b_0,transparent_26%),linear-gradient(135deg,#fff7e4_0%,#d8ead8_100%)] px-6 py-12">
            <section class="w-full max-w-6xl rounded-[2rem] bg-white/80 p-6 shadow-2xl ring-1 ring-[#213126]/10 backdrop-blur md:p-10">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.3em] text-[#6f4b25]">{{ __('Booking confirmed') }}</p>
                        <h1 class="mt-3 text-4xl font-black tracking-tight text-[#17231a] md:text-5xl">
                            {{ __('Your spot is reserved.') }}
                        </h1>
                        <p class="mt-3 max-w-2xl text-sm font-semibold leading-6 text-[#526051]">
                            {{ __('Confirmation email sent to :email.', ['email' => $booking->guest_email]) }}
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-full bg-[#d9edc8] px-4 py-2 text-sm font-black text-[#264f3a]">
                        {{ __('Booking number') }} #{{ $booking->id }}
                    </span>
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem] lg:items-start">
                    <div>
                        <div class="rounded-3xl bg-[#17231a] p-6 text-white">
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-[#f8c76b]">{{ __('Booking overview') }}</p>
                            <h2 class="mt-2 text-2xl font-black">{{ $booking->campingSpot->name }}</h2>
                            <p class="mt-2 text-white/75">
                                {{ __(':spot from :start to :end.', [
                                    'spot' => $booking->campingSpot->name,
                                    'start' => $booking->start_date->toDateString(),
                                    'end' => $booking->end_date->toDateString(),
                                ]) }}
                            </p>

                            <dl class="mt-6 grid gap-px overflow-hidden rounded-2xl bg-white/10 sm:grid-cols-2 xl:grid-cols-4">
                                <div class="bg-white/5 p-4">
                                    <dt class="text-xs font-black uppercase tracking-[0.16em] text-white/60">{{ __('Arrival') }}</dt>
                                    <dd class="mt-2 text-lg font-black">{{ $booking->start_date->toDateString() }}</dd>
                                </div>

                                <div class="bg-white/5 p-4">
                                    <dt class="text-xs font-black uppercase tracking-[0.16em] text-white/60">{{ __('Departure') }}</dt>
                                    <dd class="mt-2 text-lg font-black">{{ $booking->end_date->toDateString() }}</dd>
                                </div>

                                <div class="bg-white/5 p-4">
                                    <dt class="text-xs font-black uppercase tracking-[0.16em] text-white/60">{{ __('Stay') }}</dt>
                                    <dd class="mt-2 text-lg font-black">{{ __(':count nights', ['count' => $stayNights]) }}</dd>
                                </div>

                                <div class="bg-white/5 p-4">
                                    <dt class="text-xs font-black uppercase tracking-[0.16em] text-white/60">{{ __('Guests') }}</dt>
                                    <dd class="mt-2 text-lg font-black">{{ __(':count guests', ['count' => $booking->party_size]) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <dl class="mt-6 grid gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-[#213126]/10 bg-white p-5">
                                <dt class="text-sm font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Contact details') }}</dt>
                                <dd class="mt-3 space-y-1 text-sm font-semibold text-[#526051]">
                                    <span class="block text-lg font-black text-[#17231a]">{{ $booking->guest_name }}</span>
                                    <span class="block">{{ $booking->guest_email }}</span>
                                    <span class="block">{{ $booking->guest_phone ?: __('No phone number') }}</span>
                                </dd>
                            </div>

                            <div class="rounded-2xl border border-[#213126]/10 bg-white p-5">
                                <dt class="text-sm font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Estimated total') }}</dt>
                                <dd class="mt-3">
                                    <span class="block text-3xl font-black text-[#17231a]">
                                        {{ Illuminate\Support\Number::currency($estimatedTotal, in: 'EUR', locale: app()->getLocale()) }}
                                    </span>
                                    <span class="mt-1 block text-sm font-semibold text-[#526051]">
                                        {{ Illuminate\Support\Number::currency((float) $booking->campingSpot->price_per_night, in: 'EUR', locale: app()->getLocale()) }} {{ __('per night') }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <aside class="rounded-3xl border border-[#213126]/10 bg-white p-5 shadow-xl shadow-[#213126]/5 lg:sticky lg:top-6" aria-label="{{ __('Your reservation') }}">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[#6f4b25]">{{ __('Your reservation') }}</p>
                        <h2 class="mt-2 text-xl font-black text-[#17231a]">{{ $booking->campingSpot->name }}</h2>

                        <dl class="mt-5 divide-y divide-[#213126]/10 text-sm">
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="font-bold text-[#526051]">{{ __('Booking number') }}</dt>
                                <dd class="font-black text-[#17231a]">#{{ $booking->id }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="font-bold text-[#526051]">{{ __('Arrival') }}</dt>
                                <dd class="font-black text-[#17231a]">{{ $booking->start_date->toDateString() }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="font-bold text-[#526051]">{{ __('Departure') }}</dt>
                                <dd class="font-black text-[#17231a]">{{ $booking->end_date->toDateString() }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="font-bold text-[#526051]">{{ __('Guests') }}</dt>
                                <dd class="font-black text-[#17231a]">{{ __(':count guests', ['count' => $booking->party_size]) }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="font-bold text-[#526051]">{{ __('Stay') }}</dt>
                                <dd class="font-black text-[#17231a]">{{ __(':count nights', ['count' => $stayNights]) }}</dd>
                            </div>
                            <div class="flex items-end justify-between gap-4 py-3">
                                <dt class="font-bold text-[#526051]">{{ __('Estimated total') }}</dt>
                                <dd class="text-right text-2xl font-black text-[#17231a]">
                                    {{ Illuminate\Support\Number::currency($estimatedTotal, in: 'EUR', locale: app()->getLocale()) }}
                                </dd>
                            </div>
                        </dl>

                        <a class="mt-5 inline-flex w-full justify-center rounded-2xl bg-[#f8c76b] px-5 py-3 text-sm font-black uppercase tracking-[0.2em] text-[#17231a] transition hover:-translate-y-0.5 hover:bg-[#ffd982]" href="{{ route('bookings.create') }}">
                            {{ __('New booking') }}
                        </a>
                    </aside>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a class="inline-flex justify-center rounded-2xl border border-[#213126]/10 bg-white px-5 py-3 text-sm font-black uppercase tracking-[0.2em] text-[#003b73] transition hover:-translate-y-0.5 hover:bg-[#eef4ff]" href="{{ route('bookings.create') }}">
                        {{ __('Back to results') }}
                    </a>
                </div>
            </section>
        </main>
    </body>
</html>
