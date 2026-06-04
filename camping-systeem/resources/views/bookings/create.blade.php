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
    <body class="min-h-screen bg-[#f3ead8] text-[#213126] antialiased">
        <main id="home" class="relative isolate overflow-hidden">
            @php
                $languageButtons = [
                    'de' => 'Deutsch',
                    'nl' => 'Nederlands',
                    'en' => 'English',
                ];
            @endphp

            <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_12%_8%,#f8c76b_0,transparent_28%),radial-gradient(circle_at_86%_18%,#6fa28b_0,transparent_24%),linear-gradient(135deg,#fff7e4_0%,#d8ead8_54%,#f2d29f_100%)]"></div>
            <div class="absolute left-1/2 top-24 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-[#264f3a]/10 blur-3xl"></div>

            <header class="w-full px-4 pt-4 sm:px-6 lg:px-8">
                <nav class="flex w-full flex-col gap-4 rounded-4xl border border-[#213126]/10 bg-white/60 p-3 shadow-xl shadow-[#213126]/5 backdrop-blur md:flex-row md:items-center md:justify-between" aria-label="{{ __('Main navigation') }}">
                    
                        <span class="grid size-12 place-items-center rounded-2xl bg-[#17231a] text-sm font-black uppercase tracking-[0.12em] text-[#f8c76b] shadow-lg">
                            <img src="images/vuurvlieg.jpg" alt="Logo" class="h-10 w-10 object-contain">
                        </span>
                        <span class="leading-tight">
                            <span class="block text-sm font-black uppercase tracking-[0.22em] text-[#6f4b25]">{{ __('Camping') }}</span>
                            <span class="block text-lg font-black text-[#17231a]">De Vuurvlieg</span>
                        </span>
                    </a>

                    <div class="flex flex-wrap items-center gap-2 text-sm font-black text-[#213126]">
                        <a href="/" class="rounded-full px-4 py-2 transition hover:bg-[#17231a] hover:text-white focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">{{ __('Home') }}</a>
                        <a href="{{ route('bookings.create') }}" class="rounded-full px-4 py-2 transition hover:bg-[#17231a] hover:text-white focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">{{ __('Boek') }}</a>
                        <a href="#contact" class="rounded-full px-4 py-2 transition hover:bg-[#17231a] hover:text-white focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">{{ __('Contact') }}</a>
                        <a href="#info" class="rounded-full px-4 py-2 transition hover:bg-[#17231a] hover:text-white focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">{{ __('Info') }}</a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2" aria-label="{{ __('Language choice') }}">
                        @foreach ($languageButtons as $locale => $label)
                            <a
                                href="{{ route('locale.update', $locale) }}"
                                @class([
                                    'rounded-full px-4 py-2 text-xs font-black uppercase tracking-[0.16em] shadow-sm transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15',
                                    'bg-[#17231a] text-white hover:bg-[#264f3a]' => app()->getLocale() === $locale,
                                    'border border-[#213126]/10 bg-white/70 text-[#213126] hover:bg-[#f8c76b]' => app()->getLocale() !== $locale,
                                ])
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </nav>
            </header>

            <section class="mx-auto grid min-h-screen w-full max-w-7xl gap-10 px-6 py-10 lg:grid-cols-[0.9fr_1.1fr] lg:px-10 lg:py-14">
                <div id="info" class="flex scroll-mt-32 flex-col justify-between gap-10">
                    <div class="space-y-8">
                        <div class="inline-flex w-fit items-center rounded-full border border-[#213126]/15 bg-white/45 px-4 py-2 text-sm font-semibold shadow-sm backdrop-blur">
                            Camping De Vuurvlieg
                        </div>

                        <div class="space-y-5">
                            <p class="text-sm font-bold uppercase tracking-[0.35em] text-[#6f4b25]">{{ __('Direct boeken') }}</p>
                            <h1 class="max-w-3xl text-5xl font-black leading-[0.95] tracking-tight text-[#17231a] sm:text-6xl lg:text-7xl">
                                {{ __('Vind je plek tussen bos, duinen en het kampvuur.') }}
                            </h1>
                            <p class="max-w-xl text-lg leading-8 text-[#415143]">
                                {{ __('Kies je data, filter op groepsgrootte en reserveer direct een beschikbare kampeerplek.') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl bg-[#17231a] p-5 text-white shadow-xl">
                            <p class="text-3xl font-black">{{ $campingSpots->count() }}</p>
                            <p class="mt-1 text-sm text-white/70">{{ __('zichtbare plekken') }}</p>
                        </div>
                        <div class="rounded-3xl bg-white/55 p-5 shadow-sm ring-1 ring-[#213126]/10 backdrop-blur">
                            <p class="text-3xl font-black">24/7</p>
                            <p class="mt-1 text-sm text-[#526051]">{{ __('online aanvraag') }}</p>
                        </div>
                        <div class="rounded-3xl bg-[#dd8d3b] p-5 text-[#221407] shadow-xl">
                            <p class="text-3xl font-black">1</p>
                            <p class="mt-1 text-sm text-[#221407]/70">{{ __('formulier') }}</p>
                        </div>
                    </div>
                </div>

                <div id="boeken" class="scroll-mt-32 space-y-6">
                    <form method="GET" action="{{ route('bookings.create') }}" class="rounded-4xl bg-white/70 p-5 shadow-2xl ring-1 ring-[#213126]/10 backdrop-blur md:p-7">
                        <div class="grid gap-4 md:grid-cols-3">
                            <label class="space-y-2">
                                <span class="text-sm font-bold">{{ __('Aankomst') }}</span>
                                <input class="w-full rounded-2xl border border-[#213126]/15 bg-white px-4 py-3 outline-none transition focus:border-[#264f3a] focus:ring-4 focus:ring-[#264f3a]/10" type="date" name="start_date" value="{{ request('start_date') }}">
                            </label>

                            <label class="space-y-2">
                                <span class="text-sm font-bold">{{ __('Vertrek') }}</span>
                                <input class="w-full rounded-2xl border border-[#213126]/15 bg-white px-4 py-3 outline-none transition focus:border-[#264f3a] focus:ring-4 focus:ring-[#264f3a]/10" type="date" name="end_date" value="{{ request('end_date') }}">
                            </label>

                            <label class="space-y-2">
                                <span class="text-sm font-bold">{{ __('Personen') }}</span>
                                <input class="w-full rounded-2xl border border-[#213126]/15 bg-white px-4 py-3 outline-none transition focus:border-[#264f3a] focus:ring-4 focus:ring-[#264f3a]/10" type="number" name="party_size" min="1" value="{{ request('party_size', 2) }}">
                            </label>
                        </div>

                        <button class="mt-5 w-full rounded-2xl bg-[#17231a] px-5 py-4 text-sm font-black uppercase tracking-[0.2em] text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-[#264f3a]" type="submit">
                            {{ __('Controleer beschikbaarheid') }}
                        </button>
                    </form>

                    @if ($hasAvailabilitySearch)
                        <p class="text-sm font-semibold text-[#415143]">
                            {{ __('Beschikbare plekken van :start tot :end.', ['start' => request('start_date'), 'end' => request('end_date')]) }}
                        </p>
                    @endif

                    <div class="grid gap-4 md:grid-cols-2">
                        @forelse ($campingSpots as $campingSpot)
                            <article class="rounded-[1.75rem] bg-white/65 p-5 shadow-sm ring-1 ring-[#213126]/10 backdrop-blur">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h2 class="text-xl font-black">{{ $campingSpot->name }}</h2>
                                        <p class="mt-2 text-sm leading-6 text-[#526051]">{{ $campingSpot->description ?? __('Rustige kampeerplek met voldoende ruimte voor tent of caravan.') }}</p>
                                    </div>
                                    <span class="rounded-full bg-[#d9edc8] px-3 py-1 text-xs font-black text-[#264f3a]">{{ __(':count guests', ['count' => $campingSpot->capacity]) }}</span>
                                </div>
                            </article>
                        @empty
                            <div class="md:col-span-2 rounded-[1.75rem] bg-white/65 p-6 text-center shadow-sm ring-1 ring-[#213126]/10 backdrop-blur">
                                <h2 class="text-xl font-black">{{ __('Geen plekken gevonden') }}</h2>
                                <p class="mt-2 text-sm text-[#526051]">{{ __('Probeer een andere periode of een kleinere groepsgrootte.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <form id="contact" method="POST" action="{{ route('bookings.store') }}" class="scroll-mt-32 rounded-4xl bg-[#17231a] p-5 text-white shadow-2xl md:p-7">
                        @csrf

                        <div class="mb-6">
                            <p class="text-sm font-bold uppercase tracking-[0.3em] text-[#f2d29f]">{{ __('Reserveren') }}</p>
                            <h2 class="mt-2 text-3xl font-black">{{ __('Rond je reservering af') }}</h2>
                        </div>

                        @if ($errors->any())
                            <div class="mb-5 rounded-2xl bg-[#f8c76b] p-4 text-sm font-semibold text-[#221407]">
                                {{ __('Controleer de gemarkeerde velden en probeer het opnieuw.') }}
                            </div>
                        @endif

                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="space-y-2 md:col-span-2">
                                <span class="text-sm font-bold">{{ __('Kampeerplek') }}</span>
                                <select class="w-full rounded-2xl border border-white/10 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-white/20" name="camping_spot_id" required>
                                    <option value="">{{ __('Kies een plek') }}</option>
                                    @foreach ($campingSpots as $campingSpot)
                                        <option value="{{ $campingSpot->id }}" @selected((int) old('camping_spot_id') === $campingSpot->id)>
                                            {{ __(':name - max. :count personen', ['name' => $campingSpot->name, 'count' => $campingSpot->capacity]) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('camping_spot_id')
                                    <span class="text-sm font-semibold text-[#f8c76b]">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="space-y-2">
                                <span class="text-sm font-bold">{{ __('Naam') }}</span>
                                <input class="w-full rounded-2xl border border-white/10 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-white/20" type="text" name="guest_name" value="{{ old('guest_name') }}" required>
                                @error('guest_name')
                                    <span class="text-sm font-semibold text-[#f8c76b]">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="space-y-2">
                                <span class="text-sm font-bold">{{ __('E-mail') }}</span>
                                <input class="w-full rounded-2xl border border-white/10 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-white/20" type="email" name="guest_email" value="{{ old('guest_email') }}" required>
                                @error('guest_email')
                                    <span class="text-sm font-semibold text-[#f8c76b]">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="space-y-2">
                                <span class="text-sm font-bold">{{ __('Telefoon') }}</span>
                                <input class="w-full rounded-2xl border border-white/10 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-white/20" type="text" name="guest_phone" value="{{ old('guest_phone') }}">
                                @error('guest_phone')
                                    <span class="text-sm font-semibold text-[#f8c76b]">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="space-y-2">
                                <span class="text-sm font-bold">{{ __('Personen') }}</span>
                                <input class="w-full rounded-2xl border border-white/10 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-white/20" type="number" name="party_size" min="1" value="{{ old('party_size', request('party_size', 2)) }}" required>
                                @error('party_size')
                                    <span class="text-sm font-semibold text-[#f8c76b]">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="space-y-2">
                                <span class="text-sm font-bold">{{ __('Aankomst') }}</span>
                                <input class="w-full rounded-2xl border border-white/10 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-white/20" type="date" name="start_date" value="{{ old('start_date', request('start_date')) }}" required>
                                @error('start_date')
                                    <span class="text-sm font-semibold text-[#f8c76b]">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="space-y-2">
                                <span class="text-sm font-bold">{{ __('Vertrek') }}</span>
                                <input class="w-full rounded-2xl border border-white/10 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-white/20" type="date" name="end_date" value="{{ old('end_date', request('end_date')) }}" required>
                                @error('end_date')
                                    <span class="text-sm font-semibold text-[#f8c76b]">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="space-y-2 md:col-span-2">
                                <span class="text-sm font-bold">{{ __('Opmerking') }}</span>
                                <textarea class="min-h-28 w-full rounded-2xl border border-white/10 bg-white px-4 py-3 text-[#213126] outline-none transition focus:ring-4 focus:ring-white/20" name="notes">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="text-sm font-semibold text-[#f8c76b]">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>

                        <button class="mt-6 w-full rounded-2xl bg-[#f8c76b] px-5 py-4 text-sm font-black uppercase tracking-[0.2em] text-[#17231a] shadow-lg transition hover:-translate-y-0.5 hover:bg-[#ffd982]" type="submit">
                            {{ __('Reserveer') }}
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
