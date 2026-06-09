<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Boek nu - Camping De Vuurvlieg') }}</title>

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite('resources/css/app.css')
        @endif
    </head>
    <body class="min-h-screen bg-white">
        <main id="home" class="relative isolate overflow-hidden">
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
            @endphp

            <div class="absolute inset-0 -z-10 bg-white"></div>
            <div class="absolute left-1/2 top-24 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-[#264f3a]/10 blur-3xl"></div>

            <header class="w-full px-4 pt-4 sm:px-6 lg:px-8">
                <nav class="flex w-full flex-col gap-4 rounded-4xl border border-[#213126]/10 bg-[#003b73] p-3 shadow-xl shadow-[#213126]/5 backdrop-blur md:flex-row md:items-center md:justify-between" aria-label="{{ __('Main navigation') }}">

                            <div class="flex items-center gap-6">
                    <span class="grid size-12 place-items-center rounded-2xl bg-[#17231a] text-sm font-black uppercase tracking-[0.12em] text-[#f8c76b] shadow-lg">
                        <img src="images/vuurvlieg.jpg" alt="Logo" class="h-10 w-10 object-contain">
                    </span>

                                        <span class="leading-tight text-left">
                        <span class="block text-sm font-black uppercase tracking-[0.22em] text-[#6f4b25]">
                            {{ __('Camping') }}
                        </span>
                        <span class="block text-lg font-black text-[#17231a]">
                            De Vuurvlieg
                        </span>
                    </span>
                            </div>


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

            <section class="mx-auto min-h-screen w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div class="mt-3 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                </div>

                <div id="boeken" class="grid scroll-mt-32 gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
                    <aside class="lg:sticky lg:top-6 lg:self-start">
                        <form method="GET" action="{{ route('bookings.create') }}" class="rounded-2xl bg-[#f8c76b] p-4 shadow-lg ring-1 ring-[#17231a]/10">
                            <div class="mb-4">
                                <p class="text-lg font-black text-[#17231a]">{{ __('Filter your search') }}</p>
                                <p class="mt-1 text-sm font-semibold text-[#513b12]">{{ __('Choose dates, guests and budget.') }}</p>
                            </div>

                            <div class="grid gap-4">
                                <label class="space-y-1.5">
                                    <span class="text-sm font-black text-[#17231a]">{{ __('Arrival') }}</span>
                                    <input class="w-full rounded-xl border border-[#17231a]/20 bg-white px-3 py-3 text-[#17231a] outline-none focus:ring-4 focus:ring-white/60" type="date" name="start_date" value="{{ request('start_date') }}">
                                </label>

                                <label class="space-y-1.5">
                                    <span class="text-sm font-black text-[#17231a]">{{ __('Departure') }}</span>
                                    <input class="w-full rounded-xl border border-[#17231a]/20 bg-white px-3 py-3 text-[#17231a] outline-none focus:ring-4 focus:ring-white/60" type="date" name="end_date" value="{{ request('end_date') }}">
                                </label>

                                <label class="space-y-1.5">
                                    <span class="text-sm font-black text-[#17231a]">{{ __('Guests') }}</span>
                                    <input class="w-full rounded-xl border border-[#17231a]/20 bg-white px-3 py-3 text-[#17231a] outline-none focus:ring-4 focus:ring-white/60" type="number" name="party_size" min="1" value="{{ request('party_size', 2) }}">
                                </label>

                                <fieldset class="rounded-xl bg-white/70 p-3 ring-1 ring-[#17231a]/10">
                                    <legend class="text-sm font-black text-[#17231a]">{{ __('Accommodation type') }}</legend>
                                    <div class="mt-3 grid gap-2">
                                        @foreach ($accommodationTypeLabels as $type => $label)
                                            <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-[#17231a]">
                                                <input class="mt-1 size-4 rounded border-[#003b73] text-[#003b73]" type="checkbox" name="accommodation_types[]" value="{{ $type }}" @checked(in_array($type, $selectedAccommodationTypes, true))>
                                                <span class="font-black">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </fieldset>

                                <fieldset class="rounded-xl bg-white/70 p-3 ring-1 ring-[#17231a]/10">
                                    <legend class="text-sm font-black text-[#17231a]">{{ __('Price range') }}</legend>
                                    <div class="mt-3 grid gap-2">
                                        <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-[#17231a]">
                                            <input class="mt-1 size-4 rounded border-[#003b73] text-[#003b73]" type="checkbox" name="price_ranges[]" value="budget" @checked(in_array('budget', $selectedPriceRanges, true))>
                                            <span>
                                                <span class="block font-black">{{ __('Budget') }}</span>
                                                <span class="block text-xs text-[#513b12]">{{ __('Less than €40 per night') }}</span>
                                            </span>
                                        </label>

                                        <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-[#17231a]">
                                            <input class="mt-1 size-4 rounded border-[#003b73] text-[#003b73]" type="checkbox" name="price_ranges[]" value="standard" @checked(in_array('standard', $selectedPriceRanges, true))>
                                            <span>
                                                <span class="block font-black">{{ __('Standard') }}</span>
                                                <span class="block text-xs text-[#513b12]">{{ __('€40 to €60 per night') }}</span>
                                            </span>
                                        </label>

                                        <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-[#17231a]">
                                            <input class="mt-1 size-4 rounded border-[#003b73] text-[#003b73]" type="checkbox" name="price_ranges[]" value="premium" @checked(in_array('premium', $selectedPriceRanges, true))>
                                            <span>
                                                <span class="block font-black">{{ __('Premium') }}</span>
                                                <span class="block text-xs text-[#513b12]">{{ __('More than €60 per night') }}</span>
                                            </span>
                                        </label>
                                    </div>
                                </fieldset>

                                <fieldset class="rounded-xl bg-white/70 p-3 ring-1 ring-[#17231a]/10">
                                    <legend class="text-sm font-black text-[#17231a]">{{ __('Capacity') }}</legend>
                                    <div class="mt-3 grid gap-2">
                                        <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-[#17231a]">
                                            <input class="mt-1 size-4 rounded border-[#003b73] text-[#003b73]" type="checkbox" name="capacity_ranges[]" value="small" @checked(in_array('small', $selectedCapacityRanges, true))>
                                            <span>
                                                <span class="block font-black">{{ __('Small spot') }}</span>
                                                <span class="block text-xs text-[#513b12]">{{ __('Up to 3 guests') }}</span>
                                            </span>
                                        </label>

                                        <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-[#17231a]">
                                            <input class="mt-1 size-4 rounded border-[#003b73] text-[#003b73]" type="checkbox" name="capacity_ranges[]" value="medium" @checked(in_array('medium', $selectedCapacityRanges, true))>
                                            <span>
                                                <span class="block font-black">{{ __('Family spot') }}</span>
                                                <span class="block text-xs text-[#513b12]">{{ __('4 to 5 guests') }}</span>
                                            </span>
                                        </label>

                                        <label class="flex cursor-pointer items-start gap-3 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-[#17231a]">
                                            <input class="mt-1 size-4 rounded border-[#003b73] text-[#003b73]" type="checkbox" name="capacity_ranges[]" value="large" @checked(in_array('large', $selectedCapacityRanges, true))>
                                            <span>
                                                <span class="block font-black">{{ __('Large spot') }}</span>
                                                <span class="block text-xs text-[#513b12]">{{ __('6 or more guests') }}</span>
                                            </span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>

                            <button class="mt-5 w-full rounded-xl bg-[#003b73] px-4 py-3 text-sm font-black uppercase tracking-[0.16em] text-white transition hover:bg-[#17231a]" type="submit">
                                {{ __('Check availability') }}
                            </button>

                            <a class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-3 text-sm font-black text-[#003b73] transition hover:bg-[#fff7df]" href="{{ route('bookings.create') }}">
                                {{ __('Reset filters') }}
                            </a>
                        </form>
                    </aside>

                    <div class="space-y-6">
                        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-[#213126]/10">
                            <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-sm font-black uppercase tracking-[0.18em] text-[#6f4b25]">{{ __('Search results') }}</p>
                                    <h2 class="mt-1 text-2xl font-black text-[#17231a]">{{ __('Available occasions') }}</h2>
                                    @if ($hasAvailabilitySearch)
                                        <p class="mt-1 text-sm font-semibold text-[#526051]">
                                            {{ __('Available spots from :start to :end.', ['start' => request('start_date'), 'end' => request('end_date')]) }}
                                        </p>
                                    @else
                                        <p class="mt-1 text-sm font-semibold text-[#526051]">{{ __('Select dates to check exact availability.') }}</p>
                                    @endif
                                </div>
                                <p class="rounded-full bg-[#d9edc8] px-4 py-2 text-sm font-black text-[#264f3a]">
                                    {{ __(':count results', ['count' => $campingSpots->count()]) }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @forelse ($campingSpots as $campingSpot)
                                @php
                                    $pricePerNight = (float) $campingSpot->price_per_night;
                                @endphp

                                <a
                                    href="{{ route('bookings.spots.show', array_merge(['campingSpot' => $campingSpot], request()->only(['start_date', 'end_date', 'party_size', 'accommodation_types', 'price_ranges', 'capacity_ranges']))) }}"
                                    class="group grid gap-4 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-[#213126]/10 transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-[#003b73]/15 md:grid-cols-[180px_minmax(0,1fr)_170px] md:p-5"
                                >
                                    <img src="{{ asset('images/Kampvuur-avond.jpg') }}" alt="{{ $campingSpot->name }}" class="h-40 w-full rounded-xl object-cover md:h-full">

                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-2xl font-black text-[#003b73] group-hover:underline">{{ $campingSpot->name }}</h3>
                                            <span class="rounded-full bg-[#eef4ff] px-3 py-1 text-xs font-black text-[#003b73]">{{ $accommodationTypeLabels[$campingSpot->accommodation_type] ?? __('Camping pitch') }}</span>
                                            <span class="rounded-full bg-[#d9edc8] px-3 py-1 text-xs font-black text-[#264f3a]">{{ __(':count guests', ['count' => $campingSpot->capacity]) }}</span>
                                        </div>
                                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-[#526051]">{{ $campingSpot->description ?? __('Quiet camping spot with enough room for your tent or caravan.') }}</p>
                                        <div class="mt-4 flex flex-wrap gap-2 text-xs font-black uppercase tracking-[0.14em]">
                                            <span class="rounded-full bg-[#eef4ff] px-3 py-1 text-[#003b73]">{{ $accommodationTypeLabels[$campingSpot->accommodation_type] ?? __('Camping pitch') }}</span>
                                            <span class="rounded-full bg-[#fff4d6] px-3 py-1 text-[#6f4b25]">{{ __('Instant request') }}</span>
                                        </div>
                                        <p class="mt-4 text-sm font-black text-[#003b73]">{{ __('Click for details and prices') }}</p>
                                    </div>

                                    <div class="flex flex-col justify-between gap-4 text-left md:text-right">
                                        <div>
                                            <p class="text-xs font-bold text-[#526051]">{{ __('From') }}</p>
                                            <p class="text-2xl font-black text-[#17231a]">{{ Illuminate\Support\Number::currency($pricePerNight, in: 'EUR', locale: app()->getLocale()) }}</p>
                                            <p class="text-xs font-semibold text-[#526051]">{{ __('per night') }}</p>
                                        </div>
                                        <span class="inline-flex justify-center rounded-xl bg-[#003b73] px-4 py-3 text-sm font-black text-white transition group-hover:bg-[#17231a]">
                                            {{ __('View details') }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-[#213126]/10">
                                    <h2 class="text-xl font-black text-[#17231a]">{{ __('No spots found') }}</h2>
                                    <p class="mt-2 text-sm text-[#526051]">{{ __('Try another period or a smaller group size.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
