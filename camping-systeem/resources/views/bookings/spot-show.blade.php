<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $campingSpot->name }} - {{ __('Camping De Vuurvlieg') }}</title>

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite('resources/css/app.css')
        @endif
    </head>
    <body class="min-h-screen bg-[#f7f8f5] text-[#17231a]">
        @php
            $languageButtons = [
                'de' => 'Deutsch',
                'nl' => 'Nederlands',
                'en' => 'English',
            ];

            $accommodationTypeLabels = [
                App\Models\CampingSpot::TYPE_TENT_PITCH => __('Tent pitch'),
                App\Models\CampingSpot::TYPE_CHALET => __('Chalet'),
                App\Models\CampingSpot::TYPE_STATIC_CARAVAN => __('Static caravan'),
                App\Models\CampingSpot::TYPE_CAMPING_PITCH => __('Camping pitch'),
            ];

            $pricePerNight = (float) $campingSpot->price_per_night;
            $totalPrice = $stayNights ? $pricePerNight * $stayNights : null;
        @endphp

        <main class="relative isolate overflow-hidden">
            <div class="absolute inset-0 -z-10 bg-[#F5F5DC]"></div>

            <header class="w-full px-4 pt-4 sm:px-6 lg:px-8">
                <nav class="flex w-full flex-col gap-4 rounded-4xl border border-[#213126]/10 bg-white p-3 shadow-xl shadow-[#213126]/5 backdrop-blur md:flex-row md:items-center md:justify-between" aria-label="{{ __('Main navigation') }}">
                    <a href="{{ route('bookings.create', request()->only(['start_date', 'end_date', 'party_size', 'accommodation_types', 'price_ranges', 'capacity_ranges'])) }}" class="flex items-center gap-6">
                        <span class="grid size-12 place-items-center rounded-2xl bg-[#17231a] text-sm font-black uppercase tracking-[0.12em] text-[#f8c76b] shadow-lg">
                            <img src="{{ asset('images/vuurvlieg.jpg') }}" alt="Logo" class="h-10 w-10 object-contain">
                        </span>

                        <span class="leading-tight text-left">
                            <span class="block text-sm font-black uppercase tracking-[0.22em] text-[#6f4b25]">
                                {{ __('Camping') }}
                            </span>
                            <span class="block text-lg font-black text-[#17231a]">
                                De Vuurvlieg
                            </span>
                        </span>
                    </a>

                    <div class="flex flex-wrap items-center gap-2" aria-label="{{ __('Language choice') }}">
                        @foreach ($languageButtons as $locale => $label)
                            @php
                                $flag = "images/{$locale}.png";
                                if ($locale === 'nl') $flag = 'images/nederland-vlag.png';
                                if ($locale === 'en') $flag = 'images/engeland-vlag.png';
                                if ($locale === 'de') $flag = 'images/duitsland-vlag.png';
                            @endphp

                            <a
                                href="{{ route('locale.update', $locale) }}"
                                @class([
                                    'rounded-full px-3 py-1.5 text-xs font-black uppercase tracking-[0.16em] shadow-sm transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15 flex items-center gap-2',
                                    'bg-[#17231a] text-white hover:bg-[#264f3a]' => app()->getLocale() === $locale,
                                    'border border-[#213126]/10 bg-white/70 text-[#213126] hover:bg-[#f8c76b]' => app()->getLocale() !== $locale,
                                ])
                            >
                                <img src="{{ asset($flag) }}" alt="{{ $label }}" class="h-7 w-9 object-contain">
                            </a>
                        @endforeach
                    </div>
                </nav>
            </header>

            <section class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <a class="mb-5 inline-flex rounded-xl bg-white px-4 py-3 text-sm font-black text-[#003b73] shadow-sm ring-1 ring-[#213126]/10 transition hover:bg-[#eef4ff]" href="{{ route('bookings.create', request()->only(['start_date', 'end_date', 'party_size', 'accommodation_types', 'price_ranges', 'capacity_ranges'])) }}">
                    {{ __('Back to results') }}
                </a>

                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_380px]">
                    <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-[#213126]/10">
                        <img src="{{ asset('images/Kampvuur-avond.jpg') }}" alt="{{ $campingSpot->name }}" class="h-72 w-full object-cover md:h-96">

                        <div class="p-5 md:p-7">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full bg-[#eef4ff] px-3 py-1 text-xs font-black text-[#003b73]">{{ $accommodationTypeLabels[$campingSpot->accommodation_type] ?? __('Camping pitch') }}</span>
                                <span class="rounded-full bg-[#d9edc8] px-3 py-1 text-xs font-black text-[#264f3a]">{{ __(':count guests', ['count' => $campingSpot->capacity]) }}</span>
                                @if ($isAvailable === true)
                                    <span class="rounded-full bg-[#d9edc8] px-3 py-1 text-xs font-black text-[#264f3a]">{{ __('Available') }}</span>
                                @elseif ($isAvailable === false)
                                    <span class="rounded-full bg-[#ffe1d6] px-3 py-1 text-xs font-black text-[#8a321c]">{{ __('Not available for these dates') }}</span>
                                @endif
                            </div>

                            <h1 class="mt-4 text-3xl font-black text-[#003b73] md:text-5xl">{{ $campingSpot->name }}</h1>
                            <p class="mt-4 max-w-3xl text-base leading-7 text-[#526051]">{{ $campingSpot->description ?? __('Quiet camping spot with enough room for your tent or caravan.') }}</p>

                            <div class="mt-8 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-xl bg-[#f7f8f5] p-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Price per night') }}</p>
                                    <p class="mt-2 text-2xl font-black">{{ Illuminate\Support\Number::currency($pricePerNight, in: 'EUR', locale: app()->getLocale()) }}</p>
                                </div>
                                <div class="rounded-xl bg-[#f7f8f5] p-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Capacity') }}</p>
                                    <p class="mt-2 text-2xl font-black">{{ __(':count guests', ['count' => $campingSpot->capacity]) }}</p>
                                </div>
                                <div class="rounded-xl bg-[#f7f8f5] p-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Stay') }}</p>
                                    <p class="mt-2 text-2xl font-black">{{ $stayNights ? __(':count nights', ['count' => $stayNights]) : __('Choose dates') }}</p>
                                </div>
                            </div>
                        </div>
                    </article>

                    <aside class="lg:sticky lg:top-6 lg:self-start">
                        <div class="rounded-2xl bg-white p-5 shadow-lg ring-1 ring-[#213126]/10">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-[#6f4b25]">{{ __('Total estimate') }}</p>
                            <p class="mt-2 text-3xl font-black text-[#17231a]">
                                {{ $totalPrice ? Illuminate\Support\Number::currency($totalPrice, in: 'EUR', locale: app()->getLocale()) : Illuminate\Support\Number::currency($pricePerNight, in: 'EUR', locale: app()->getLocale()) }}
                            </p>
                            <p class="mt-1 text-sm font-semibold text-[#526051]">{{ $stayNights ? __('based on selected dates') : __('per night, choose dates for total') }}</p>

                            <form method="POST" action="{{ route('bookings.store') }}" class="mt-5 grid gap-4">
                                @csrf

                                <input type="hidden" name="camping_spot_id" value="{{ $campingSpot->id }}">

                                @if ($errors->any())
                                    <div class="rounded-xl bg-[#f8c76b] p-4 text-sm font-semibold text-[#221407]">
                                        {{ __('Check the highlighted fields and try again.') }}
                                    </div>
                                @endif

                                @error('camping_spot_id')
                                    <div class="rounded-xl bg-[#f8c76b] p-4 text-sm font-semibold text-[#221407]">{{ $message }}</div>
                                @enderror

                                <label class="space-y-2">
                                    <span class="text-sm font-bold">{{ __('Name') }}</span>
                                    <input class="w-full rounded-xl border border-[#17231a]/15 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-[#003b73]/15" type="text" name="guest_name" value="{{ old('guest_name') }}" required>
                                    @error('guest_name')
                                        <span class="text-sm font-semibold text-[#8a321c]">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="space-y-2">
                                    <span class="text-sm font-bold">{{ __('Email') }}</span>
                                    <input class="w-full rounded-xl border border-[#17231a]/15 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-[#003b73]/15" type="email" name="guest_email" value="{{ old('guest_email') }}" required>
                                    @error('guest_email')
                                        <span class="text-sm font-semibold text-[#8a321c]">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="space-y-2">
                                    <span class="text-sm font-bold">{{ __('Phone') }}</span>
                                    <input class="w-full rounded-xl border border-[#17231a]/15 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-[#003b73]/15" type="text" name="guest_phone" value="{{ old('guest_phone') }}">
                                    @error('guest_phone')
                                        <span class="text-sm font-semibold text-[#8a321c]">{{ $message }}</span>
                                    @enderror
                                </label>

                                <label class="space-y-2">
                                    <span class="text-sm font-bold">{{ __('Guests') }}</span>
                                    <input class="w-full rounded-xl border border-[#17231a]/15 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-[#003b73]/15" type="number" name="party_size" min="1" value="{{ old('party_size', request('party_size', 2)) }}" required>
                                    @error('party_size')
                                        <span class="text-sm font-semibold text-[#8a321c]">{{ $message }}</span>
                                    @enderror
                                </label>

                                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                                    <label class="space-y-2">
                                        <span class="text-sm font-bold">{{ __('Arrival') }}</span>
                                        <input class="w-full rounded-xl border border-[#17231a]/15 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-[#003b73]/15" type="date" name="start_date" value="{{ old('start_date', request('start_date')) }}" required>
                                        @error('start_date')
                                            <span class="text-sm font-semibold text-[#8a321c]">{{ $message }}</span>
                                        @enderror
                                    </label>

                                    <label class="space-y-2">
                                        <span class="text-sm font-bold">{{ __('Departure') }}</span>
                                        <input class="w-full rounded-xl border border-[#17231a]/15 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-[#003b73]/15" type="date" name="end_date" value="{{ old('end_date', request('end_date')) }}" required>
                                        @error('end_date')
                                            <span class="text-sm font-semibold text-[#8a321c]">{{ $message }}</span>
                                        @enderror
                                    </label>
                                </div>

                                <label class="space-y-2">
                                    <span class="text-sm font-bold">{{ __('Note') }}</span>
                                    <textarea class="min-h-24 w-full rounded-xl border border-[#17231a]/15 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-[#003b73]/15" name="notes">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="text-sm font-semibold text-[#8a321c]">{{ $message }}</span>
                                    @enderror
                                </label>

                                <button class="rounded-xl bg-[#003b73] px-5 py-4 text-sm font-black uppercase tracking-[0.18em] text-white shadow-lg transition hover:bg-[#17231a]" type="submit">
                                    {{ __('Reserve') }}
                                </button>
                            </form>
                        </div>
                    </aside>
                </div>
            </section>
        </main>
    </body>
</html>
