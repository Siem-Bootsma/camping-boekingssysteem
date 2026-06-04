<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Boek je kampeerplek</title>

    {{-- Fonts placeholder --}}

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-[#f3ead8] text-[#213126] antialiased">
<main id="home" class="relative isolate overflow-hidden pt-[66vh] md:pt-[50vh]">
    @php
        $languageButtons = $languageButtons ?? [
            'de' => 'Deutsch',
            'nl' => 'Nederlands',
            'en' => 'English',
        ];
    @endphp

    <div class="absolute left-0 right-0 top-0 -z-10 w-full h-1/2 bg-cover bg-center" style="background-image: url('{{ asset('images/Kampvuur-avond.jpg') }}')"></div>

    <div class="absolute inset-0 -z-20 bg-[radial-gradient(circle_at_12%_8%,#f8c76b_0,transparent_28%),radial-gradient(circle_at_86%_18%,#6fa28b_0,transparent_24%),linear-gradient(135deg,#fff7e4_0%,#d8ead8_54%,#f2d29f_100%)]"></div>
    <div class="absolute left-1/2 top-24 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-[#264f3a]/10 blur-3xl"></div>

    <header class="w-full px-4 sm:px-6 lg:px-8 fixed top-5 left-0 right-0 z-50">
        <nav class="flex w-full flex-col gap-4 rounded-4xl border border-[#213126]/10 bg-white/70 p-3 shadow-xl shadow-[#213126]/5 backdrop-blur md:flex-row md:items-center md:justify-between" aria-label="{{ __('Main navigation') }}">
            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-2xl px-2 py-1 transition hover:bg-white/55 focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-[#17231a] text-sm font-black uppercase tracking-[0.12em] text-[#f8c76b] shadow-lg">
                            <img src="{{ asset('images/vuurvlieg.jpg') }}" alt="De Vuurvlieg" class="h-8 w-8 object-contain">
                        </span>
                <span class="leading-tight">
                            <span class="block text-sm font-black uppercase tracking-[0.22em] text-[#6f4b25]">{{ __('Camping') }}</span>
                            <span class="block text-lg font-black text-[#17231a]">De Vuurvlieg</span>
                        </span>
            </a>

            <div class="flex flex-wrap items-center gap-2 text-sm font-black text-[#213126]">
                <a href="/" class="rounded-full px-4 py-2 transition hover:bg-[#17231a] hover:text-white focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">{{ __('Home') }}</a>
                <a href="{{ route('bookings.create') }}" class="rounded-full px-4 py-2 transition hover:bg-[#17231a] hover:text-white focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">{{ __('Book') }}</a>
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
                        ])>
                        <img src="{{ asset($flag) }}" alt="{{ $label }}" class="h-7 w-9 object-contain">
                    </a>
                @endforeach
            </div>
        </nav>
    </header>

    <section class="mx-auto grid min-h-[150vh] w-full max-w-9xl gap-6 px-6 py-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-10 lg:py-10 pb-96">
        <div class="flex scroll-mt-32 flex-col justify-between gap-10 -mt-[85px]">
            <div class="space-y-8">
                <h1 class="max-w-3xl text-5xl font-black leading-[0.95] tracking-tight text-[#17231a] sm:text-6xl lg:text-7xl bg-white/70 p-3 shadow-xl shadow-[#213126]/5 backdrop-blur rounded-xl">
                    Welkom bij Camping De Vuurvlieg
                </h1>

                <div class="space-y-6">
                    <p class="text-sm font-bold uppercase tracking-[0.35em] text-[#6f4b25]"></p>

                    <p class="max-w-2xl text-lg leading-8 text-[#415143] bg-gray-50/70 p-4 shadow-xl shadow-[#213126]/5 backdrop-blur rounded-xl font-bold">
                        Waar natuur, rust en gezelligheid samenkomen. Geniet van ruime kampeerplaatsen midden in het groen, met comfortabele faciliteiten voor een ontspannen verblijf. Op korte afstand ligt het prachtige strand van Makkum aan het IJsselmeer, ideaal om te zwemmen, wandelen, watersporten of te genieten van een mooie zonsondergang. De perfecte plek voor een ontspannen en veelzijdige vakantie.
                    </p>
                </div>
            </div>
        <div class="flex scroll-mt-32 flex-col justify-between gap-10 -mt-[85px]">
            <div class="space-y-8">




            </div>
        </div>


            <form></form>

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl bg-[#f8c76b] p-4 text-sm font-semibold text-[#221407]">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            <div class="flex justify-center w-screen left-1/2 -translate-x-1/2 relative">
                <a href="{{ route('bookings.create') }}" class="inline-block rounded-full bg-[#264f3a] px-16 py-4 text-white shadow-lg hover:bg-[#17231a] focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">
                    Boek nu jouw kampeerplek
                </a>
            </div>
        </div>
    </section>
</main>
</body>
</html>
