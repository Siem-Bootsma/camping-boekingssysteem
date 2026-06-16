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
            $imagePath = $campingSpot->image_path ?? 'images/Kampvuur-avond.jpg';
            $galleryImages = array_values(array_unique(array_filter([
                $imagePath,
                match ($campingSpot->accommodation_type) {
                    App\Models\CampingSpot::TYPE_CHALET => 'images/chalet1.png',
                    App\Models\CampingSpot::TYPE_STATIC_CARAVAN => 'images/stacaravan1.png',
                    App\Models\CampingSpot::TYPE_TENT_PITCH => 'images/tentplaats1.png',
                    default => 'images/kampeerplek1.png',
                },
                match ($campingSpot->accommodation_type) {
                    App\Models\CampingSpot::TYPE_CHALET => 'images/chalet3.png',
                    App\Models\CampingSpot::TYPE_STATIC_CARAVAN => 'images/stacaravan3.png',
                    App\Models\CampingSpot::TYPE_TENT_PITCH => 'images/tentplaatsen3.png',
                    default => 'images/Kampvuur-avond.jpg',
                },
                'images/Kampvuur-avond.jpg',
                'images/kampeerplek1.png',
            ])));
        @endphp

        <main class="relative isolate overflow-hidden">
            <div class="absolute inset-0 -z-10 bg-[#F5F5DC]"></div>

            <header class="w-full px-4 pt-4 sm:px-6 lg:px-8">
                <nav class="flex w-full flex-col gap-4 rounded-4xl border border-[#213126]/10 bg-white p-3 shadow-xl shadow-[#213126]/5 backdrop-blur md:flex-row md:items-center md:justify-between" aria-label="{{ __('Main navigation') }}">
                    <a href="{{ route('bookings.create', request()->only(['start_date', 'end_date', 'party_size', 'accommodation_types', 'min_price', 'max_price', 'capacity_ranges'])) }}" class="flex items-center gap-6">
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

            <section class="mx-auto w-full max-w-[96rem] px-4 py-8 sm:px-6 lg:px-8">
                <a class="mb-5 inline-flex rounded-xl bg-white px-4 py-3 text-sm font-black text-[#003b73] shadow-sm ring-1 ring-[#213126]/10 transition hover:bg-[#eef4ff]" href="{{ route('bookings.create', request()->only(['start_date', 'end_date', 'party_size', 'accommodation_types', 'min_price', 'max_price', 'capacity_ranges'])) }}">
                    {{ __('Back to results') }}
                </a>

                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_380px]">
                    <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-[#213126]/10">
                        <section class="grid gap-3 p-3 lg:grid-cols-[minmax(0,2fr)_minmax(240px,1fr)]" aria-label="{{ __('Photo gallery') }}">
                            <img src="{{ asset($galleryImages[0]) }}" alt="{{ $campingSpot->name }}" class="h-80 w-full rounded-xl object-cover md:h-[30rem]">

                            <div class="hidden gap-3 md:grid md:grid-cols-2 lg:grid-cols-1">
                                @foreach (array_slice($galleryImages, 1, 2) as $galleryImage)
                                    <img src="{{ asset($galleryImage) }}" alt="{{ $campingSpot->name }}" class="h-[14.5rem] w-full rounded-xl object-cover">
                                @endforeach
                            </div>

                            <div class="grid grid-cols-2 gap-3 md:grid-cols-4 lg:col-span-2">
                                @foreach (array_slice($galleryImages, 1) as $galleryImage)
                                    <div class="relative overflow-hidden rounded-xl">
                                        <img src="{{ asset($galleryImage) }}" alt="{{ $campingSpot->name }}" class="h-28 w-full object-cover md:h-36">
                                        @if ($loop->last)
                                            <div class="absolute inset-0 grid place-items-center bg-[#17231a]/45 text-lg font-black text-white">
                                                {{ __('Show all photos') }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </section>

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

                    <aside class="grid gap-4 lg:sticky lg:top-6 lg:self-start">
                        <div class="rounded-2xl bg-white p-5 shadow-lg ring-1 ring-[#213126]/10">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-[#6f4b25]">{{ __('Your reservation') }}</p>
                            <p class="mt-1 text-lg font-black text-[#17231a]">{{ $campingSpot->name }}</p>
                            <p class="mt-2 text-3xl font-black text-[#17231a]">
                                {{ $totalPrice ? Illuminate\Support\Number::currency($totalPrice, in: 'EUR', locale: app()->getLocale()) : Illuminate\Support\Number::currency($pricePerNight, in: 'EUR', locale: app()->getLocale()) }}
                            </p>
                            <p class="mt-1 text-sm font-semibold text-[#526051]">{{ $stayNights ? __('based on selected dates') : __('per night, choose dates for total') }}</p>

                            <form method="POST" action="{{ route('bookings.store') }}" class="mt-5 grid gap-4">
                                @csrf

                                <input type="hidden" name="camping_spot_id" value="{{ $campingSpot->id }}">

                                <div class="rounded-2xl bg-[#eef4ff] p-4 ring-1 ring-[#003b73]/10">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[#003b73]">{{ __('Reservation details') }}</p>

                                    @if ($stayNights && $totalPrice)
                                        <dl class="mt-3 grid gap-3 text-sm font-semibold text-[#526051]">
                                            <div class="flex items-center justify-between gap-3">
                                                <dt>{{ __('Arrival') }}</dt>
                                                <dd class="font-black text-[#17231a]">{{ request('start_date') }}</dd>
                                            </div>
                                            <div class="flex items-center justify-between gap-3">
                                                <dt>{{ __('Departure') }}</dt>
                                                <dd class="font-black text-[#17231a]">{{ request('end_date') }}</dd>
                                            </div>
                                            <div class="flex items-center justify-between gap-3">
                                                <dt>{{ __('Stay') }}</dt>
                                                <dd class="font-black text-[#17231a]">{{ __(':count nights', ['count' => $stayNights]) }}</dd>
                                            </div>
                                            <div class="flex items-center justify-between gap-3">
                                                <dt>{{ __('Guests') }}</dt>
                                                <dd class="font-black text-[#17231a]">{{ __(':count guests', ['count' => old('party_size', request('party_size', 2))]) }}</dd>
                                            </div>
                                            <div class="flex items-center justify-between gap-3 border-t border-[#003b73]/10 pt-3">
                                                <dt>{{ __('Calculated total') }}</dt>
                                                <dd class="text-xl font-black text-[#003b73]">{{ Illuminate\Support\Number::currency($totalPrice, in: 'EUR', locale: app()->getLocale()) }}</dd>
                                            </div>
                                        </dl>
                                    @elseif (request('start_date'))
                                        <p class="mt-2 text-sm font-semibold text-[#526051]">
                                            {{ __('Selected arrival: :date. Choose a departure date to calculate the total.', ['date' => request('start_date')]) }}
                                        </p>
                                    @else
                                        <p class="mt-2 text-sm font-semibold text-[#526051]">
                                            {{ __('Choose arrival and departure dates to calculate the total.') }}
                                        </p>
                                    @endif
                                </div>

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

                        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-[#213126]/10">
                            <div class="flex items-center justify-between gap-4 p-5">
                                <div>
                                    <p class="text-2xl font-black text-[#17231a]">{{ __('Very good') }}</p>
                                    <p class="mt-1 text-sm font-semibold text-[#526051]">{{ $spotReview['review_count'] }} {{ __('reviews') }}</p>
                                </div>
                                <span class="rounded-xl bg-[#003b73] px-4 py-3 text-3xl font-black text-white" aria-label="{{ __('Review score') }}">{{ $spotReview['score'] }}</span>
                            </div>

                            <div class="border-t border-[#213126]/10 p-5">
                                <p class="text-lg font-black text-[#17231a]">{{ __('Review highlights') }}</p>
                                <blockquote class="mt-4 text-base font-semibold leading-6 text-[#17231a]">
                                    “{{ $spotReview['quote'] }}”
                                </blockquote>
                                <div class="mt-6 flex items-center gap-3">
                                    <span class="grid size-11 place-items-center rounded-full bg-[#f8c76b] text-sm font-black text-[#17231a]">{{ $spotReview['initial'] }}</span>
                                    <div>
                                        <p class="font-black text-[#17231a]">{{ $spotReview['reviewer'] }}</p>
                                        <p class="text-sm font-semibold text-[#526051]">{{ $spotReview['country'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-[#213126]/10 p-5 text-center">
                                <p class="text-xl font-black text-[#17231a]">{{ __('Best rated by guests') }}</p>
                                <span class="mt-3 inline-flex rounded-xl border border-[#213126]/25 px-3 py-2 text-2xl font-black text-[#17231a]">{{ $spotReview['best_score'] }}</span>
                            </div>
                        </div>
                    </aside>

                    <section id="availability-calendar" class="scroll-mt-6 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-[#213126]/10 lg:col-span-2 md:p-7" aria-labelledby="availability-calendar-heading">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#6f4b25]">{{ __('Availability calendar') }}</p>
                                <h2 id="availability-calendar-heading" class="mt-1 text-3xl font-black text-[#17231a] md:text-4xl">
                                    {{ $availabilityCalendar['months'][0]['label'] }} - {{ $availabilityCalendar['months'][1]['label'] }}
                                </h2>
                                <p class="mt-2 text-sm font-semibold text-[#526051]">{{ __('Click the first available day for arrival, then click a second day for departure.') }}</p>
                            </div>

                            <div class="flex gap-2">
                                <a class="rounded-xl bg-[#eef4ff] px-4 py-2 text-sm font-black text-[#003b73] shadow-sm ring-1 ring-[#003b73]/10 transition hover:bg-white" href="{{ $availabilityCalendar['previousMonthUrl'] }}">
                                    {{ __('Previous') }}
                                </a>
                                <a class="rounded-xl bg-[#eef4ff] px-4 py-2 text-sm font-black text-[#003b73] shadow-sm ring-1 ring-[#003b73]/10 transition hover:bg-white" href="{{ $availabilityCalendar['nextMonthUrl'] }}">
                                    {{ __('Next') }}
                                </a>
                            </div>
                        </div>

                        <div class="mt-7 grid gap-7 2xl:grid-cols-2">
                            @foreach ($availabilityCalendar['months'] as $month)
                                <div class="min-w-0 rounded-2xl bg-[#f7f8f5] p-4 ring-1 ring-[#213126]/10 md:p-6">
                                    <h3 class="text-2xl font-black text-[#003b73]">{{ $month['label'] }}</h3>

                                    <div class="mt-5 grid grid-cols-7 gap-2 text-center text-xs font-black uppercase tracking-[0.12em] text-[#6f4b25] md:gap-3 md:text-sm">
                                        <span>{{ __('Mon') }}</span>
                                        <span>{{ __('Tue') }}</span>
                                        <span>{{ __('Wed') }}</span>
                                        <span>{{ __('Thu') }}</span>
                                        <span>{{ __('Fri') }}</span>
                                        <span>{{ __('Sat') }}</span>
                                        <span>{{ __('Sun') }}</span>
                                    </div>

                                    <div class="mt-3 grid grid-cols-7 gap-2 md:gap-3">
                                        @foreach ($month['days'] as $day)
                                            @php
                                                $date = $day['date'];
                                                $dateLabel = $date->toDateString();
                                                $dayStatus = match (true) {
                                                    ! $day['isCurrentMonth'] => __('Other month'),
                                                    $day['isPast'] => __('Past'),
                                                    $day['isBooked'] => __('Booked'),
                                                    default => __('Available'),
                                                };
                                                $dayPrice = Illuminate\Support\Number::currency($pricePerNight, in: 'EUR', locale: app()->getLocale());
                                                $selectionLabel = $day['selectsEndDate']
                                                    ? __('Choose :date as departure date', ['date' => $dateLabel])
                                                    : __('Choose :date as arrival date', ['date' => $dateLabel]);
                                                $isSelectedDay = $day['isSelectedStart'] || $day['isSelectedEnd'] || $day['isInSelectedRange'];
                                            @endphp

                                            @if ($day['selectUrl'])
                                                <a
                                                    href="{{ $day['selectUrl'] }}"
                                                    @class([
                                                        'group flex min-h-36 flex-col justify-between rounded-2xl border p-3 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-[#003b73]/15 md:min-h-40',
                                                        'border-[#003b73] bg-[#003b73] text-white' => $isSelectedDay,
                                                        'border-[#264f3a]/20 bg-white text-[#17231a] hover:border-[#003b73]/40 hover:bg-[#eef4ff]' => ! $isSelectedDay,
                                                    ])
                                                    aria-label="{{ $selectionLabel }}"
                                                >
                                                    <span class="block text-2xl font-black leading-none md:text-3xl">{{ $date->day }}</span>
                                                    <span class="block">
                                                        <span @class([
                                                            'block break-words text-sm font-black leading-tight md:text-base',
                                                            'text-white' => $isSelectedDay,
                                                            'text-[#003b73]' => ! $isSelectedDay,
                                                        ])>{{ $dayPrice }}</span>
                                                        <span @class([
                                                            'mt-1 block text-xs font-bold leading-tight',
                                                            'text-white/80' => $isSelectedDay,
                                                            'text-[#526051]' => ! $isSelectedDay,
                                                        ])>{{ __('per night') }}</span>
                                                        <span @class([
                                                            'mt-2 block truncate rounded-full px-2 py-1 text-center text-[0.55rem] font-black uppercase tracking-normal',
                                                            'bg-white/15 text-white' => $isSelectedDay,
                                                            'bg-[#d9edc8] text-[#264f3a]' => ! $isSelectedDay,
                                                        ])>{{ $dayStatus }}</span>
                                                    </span>
                                                </a>
                                            @else
                                                <div
                                                    @class([
                                                        'flex min-h-36 flex-col justify-between rounded-2xl border p-3 text-left md:min-h-40',
                                                        'border-transparent bg-white/50 text-[#9aa49a]' => ! $day['isCurrentMonth'],
                                                        'border-[#213126]/10 bg-white text-[#9aa49a]' => $day['isPast'] && $day['isCurrentMonth'],
                                                        'border-[#8a321c]/20 bg-[#fff1eb] text-[#8a321c]' => $day['isBooked'] && ! $day['isPast'] && $day['isCurrentMonth'],
                                                    ])
                                                    aria-label="{{ $dateLabel }} - {{ $dayStatus }}"
                                                >
                                                    <span class="block text-2xl font-black leading-none md:text-3xl">{{ $date->day }}</span>
                                                    <span class="block">
                                                        @if ($day['isCurrentMonth'] && ! $day['isPast'] && ! $day['isBooked'])
                                                            <span class="block break-words text-sm font-black leading-tight md:text-base">{{ $dayPrice }}</span>
                                                        @endif
                                                        <span class="mt-2 block truncate rounded-full bg-white/70 px-2 py-1 text-center text-[0.55rem] font-black uppercase tracking-normal">{{ $dayStatus }}</span>
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2 text-xs font-black uppercase tracking-[0.12em]">
                            <span class="rounded-full bg-[#d9edc8] px-3 py-1 text-[#264f3a]">{{ __('Available') }}</span>
                            <span class="rounded-full bg-[#fff1eb] px-3 py-1 text-[#8a321c]">{{ __('Booked') }}</span>
                            <span class="rounded-full bg-[#f7f8f5] px-3 py-1 text-[#526051]">{{ __('Past') }}</span>
                        </div>
                    </section>
                </div>
            </section>
        </main>
    </body>
</html>
