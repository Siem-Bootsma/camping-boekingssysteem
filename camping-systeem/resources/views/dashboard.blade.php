<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-[#f6f1e5] text-[#17231a]">
    <header class="border-b border-[#213126]/10 bg-white/80 backdrop-blur">
        <nav class="mx-auto flex w-full max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8" aria-label="{{ __('Main navigation') }}">
            <div class="flex items-center gap-4">
                <span class="grid size-12 place-items-center rounded-lg bg-[#17231a] shadow-lg">
                    <img src="{{ asset('images/vuurvlieg.jpg') }}" alt="Logo" class="h-10 w-10 rounded-md object-cover">
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

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-lg bg-red-700 px-4 py-2 text-sm font-bold text-white transition hover:bg-red-800">
                    {{ __('Log out') }}
                </button>
            </form>
        </nav>
    </header>

    <main class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-4 py-8 sm:px-6 lg:px-8">
        <section class="flex flex-col gap-2">
            <p class="text-sm font-bold uppercase tracking-[0.3em] text-[#6f4b25]">{{ __('Dashboard') }}</p>
            <h1 class="text-3xl font-black tracking-tight text-[#17231a] md:text-4xl">
                {{ __('Reservations overview') }}
            </h1>
        </section>

        <section class="rounded-lg border border-[#213126]/10 bg-white/85 shadow-xl shadow-[#213126]/5">
            <div class="flex flex-col gap-2 border-b border-[#213126]/10 p-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-black text-[#17231a]">{{ __('Bookings') }}</h2>
                    <p class="mt-1 text-sm font-semibold text-[#6f4b25]">{{ __('All reservations from the database') }}</p>
                </div>
                <span class="text-sm font-bold text-[#6f4b25]">{{ __(':count results', ['count' => $bookings->count()]) }}</span>
            </div>

            @if (session('dashboard_status'))
                <p class="border-b border-[#213126]/10 bg-[#edf6ed] px-6 py-3 text-sm font-bold text-[#17231a]">
                    {{ session('dashboard_status') }}
                </p>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#213126]/10 text-left text-sm">
                    <thead class="bg-[#17231a] text-white">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-black">{{ __('Customer') }}</th>
                            <th scope="col" class="px-4 py-3 font-black">{{ __('Camping spot') }}</th>
                            <th scope="col" class="px-4 py-3 font-black">{{ __('Arrival') }}</th>
                            <th scope="col" class="px-4 py-3 font-black">{{ __('Departure') }}</th>
                            <th scope="col" class="px-4 py-3 font-black">{{ __('Guests') }}</th>
                            <th scope="col" class="px-4 py-3 font-black">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#213126]/10 bg-white">
                        @forelse ($bookings as $booking)
                            @php
                                $isEditing = (string) request('edit_booking') === (string) $booking->getKey();
                            @endphp
                            <tr class="align-top">
                                <td class="px-4 py-4">
                                    <p class="font-black text-[#17231a]">{{ $booking->guest_name }}</p>
                                    <p class="mt-1 text-[#6f4b25]">{{ $booking->guest_email }}</p>
                                    <p class="text-[#6f4b25]">{{ $booking->guest_phone ?: __('No phone number') }}</p>
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#17231a]">
                                    {{ $booking->campingSpot?->name ?? __('Unknown spot') }}
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#17231a]">
                                    {{ $booking->start_date->format('d-m-Y') }}
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#17231a]">
                                    {{ $booking->end_date->format('d-m-Y') }}
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#17231a]">
                                    {{ $booking->party_size }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-lg bg-[#edf6ed] px-3 py-1 text-xs font-black uppercase text-[#17231a]">
                                            {{ __($booking->status) }}
                                        </span>

                                        @if ($isEditing)
                                            <a href="{{ route('dashboard') }}" class="rounded-lg border border-[#213126]/15 bg-white px-3 py-1.5 text-xs font-black uppercase text-[#17231a] transition hover:bg-[#f6f1e5]">
                                                {{ __('Collapse') }}
                                            </a>
                                        @else
                                            <a href="{{ route('dashboard', ['edit_booking' => $booking]) }}" class="rounded-lg bg-[#17231a] px-3 py-1.5 text-xs font-black uppercase text-white transition hover:bg-[#243728]">
                                                {{ __('Edit') }}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            @if ($isEditing)
                                <tr>
                                    <td colspan="6" class="bg-[#fdfaf2] px-4 py-5">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                            <form method="POST" action="{{ route('dashboard.bookings.update', $booking) }}" class="grid flex-1 gap-4 sm:grid-cols-3 lg:grid-cols-[1fr_1fr_0.8fr_auto] lg:items-start">
                                                @csrf
                                                @method('PATCH')

                                                <label class="flex flex-col gap-2">
                                                    <span class="text-xs font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Arrival') }}</span>
                                                    <input type="date" name="start_date" value="{{ old('start_date', $booking->start_date->toDateString()) }}" class="min-h-11 rounded-lg border border-[#213126]/15 bg-white px-3 py-2 font-semibold text-[#17231a] outline-none transition focus:border-[#6f4b25]">
                                                    @error('start_date')
                                                        <span class="text-xs font-bold text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </label>

                                                <label class="flex flex-col gap-2">
                                                    <span class="text-xs font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Departure') }}</span>
                                                    <input type="date" name="end_date" value="{{ old('end_date', $booking->end_date->toDateString()) }}" class="min-h-11 rounded-lg border border-[#213126]/15 bg-white px-3 py-2 font-semibold text-[#17231a] outline-none transition focus:border-[#6f4b25]">
                                                    @error('end_date')
                                                        <span class="text-xs font-bold text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </label>

                                                <label class="flex flex-col gap-2">
                                                    <span class="text-xs font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Guests') }}</span>
                                                    <input type="number" name="party_size" min="1" max="20" value="{{ old('party_size', $booking->party_size) }}" class="min-h-11 rounded-lg border border-[#213126]/15 bg-white px-3 py-2 font-semibold text-[#17231a] outline-none transition focus:border-[#6f4b25]">
                                                    @error('party_size')
                                                        <span class="text-xs font-bold text-red-700">{{ $message }}</span>
                                                    @enderror
                                                </label>

                                                <div class="flex flex-col gap-2 sm:col-span-3 lg:col-span-1 lg:mt-7">
                                                    <button type="submit" class="min-h-11 rounded-lg bg-[#17231a] px-4 py-2 text-sm font-black text-white transition hover:bg-[#243728]">
                                                        {{ __('Edit') }}
                                                    </button>

                                                   
                                                </div>
                                            </form>

                                            <form method="POST" action="{{ route('dashboard.bookings.destroy', $booking) }}" class="lg:pt-7">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="min-h-11 rounded-lg bg-red-700 px-4 py-2 text-sm font-black text-white transition hover:bg-red-800">
                                                    {{ __('Cancel') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center font-semibold text-[#6f4b25]">
                                    {{ __('No bookings yet') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-lg border border-[#213126]/10 bg-white/85 p-6 shadow-xl shadow-[#213126]/5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-black text-[#17231a]">{{ __('Customers') }}</h2>
                    <p class="mt-1 text-sm font-semibold text-[#6f4b25]">{{ __('Customer details from reservations') }}</p>
                </div>
                <span class="text-sm font-bold text-[#6f4b25]">{{ __(':count results', ['count' => $customers->count()]) }}</span>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($customers as $customer)
                    <article class="rounded-lg border border-[#213126]/10 bg-[#fdfaf2] p-4">
                        <h3 class="text-lg font-black text-[#17231a]">{{ $customer->guest_name }}</h3>
                        <dl class="mt-4 grid gap-3 text-sm">
                            <div>
                                <dt class="font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Email') }}</dt>
                                <dd class="mt-1 font-semibold text-[#17231a]">{{ $customer->guest_email }}</dd>
                            </div>
                            <div>
                                <dt class="font-black uppercase tracking-[0.16em] text-[#6f4b25]">{{ __('Phone') }}</dt>
                                <dd class="mt-1 font-semibold text-[#17231a]">{{ $customer->guest_phone ?: __('No phone number') }}</dd>
                            </div>
                        </dl>
                    </article>
                @empty
                    <p class="rounded-lg border border-[#213126]/10 bg-[#fdfaf2] p-4 font-semibold text-[#6f4b25]">
                        {{ __('No customers yet') }}
                    </p>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
