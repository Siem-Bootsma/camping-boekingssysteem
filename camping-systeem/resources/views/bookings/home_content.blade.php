<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Boek je kampeerplek</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-[#f3ead8] text-[#213126] antialiased">
<main id="home" class="relative isolate overflow-hidden">
    @php
        $languageButtons = $languageButtons ?? [
            'de' => 'Deutsch',
            'nl' => 'Nederlands',
            'en' => 'English',
        ];
    @endphp

    <!-- Background image (behind gradient) -->
    <div class="absolute inset-0 -z-20 bg-cover bg-center" style="background-image: url('{{ asset('public/images/Kampvuur-avond.jpg') }}');"></div>
    <!-- Gradient/overlay sits above the image -->
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_12%_8%,#f8c76b_0,transparent_28%),radial-gradient(circle_at_86%_18%,#6fa28b_0,transparent_24%),linear-gradient(135deg,#fff7e4_0%,#d8ead8_54%,#f2d29f_100%)]"></div>
    <div class="absolute left-1/2 top-24 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-[#264f3a]/10 blur-3xl"></div>

    <header class="w-full px-4 pt-4 sm:px-6 lg:px-8">
        <nav class="flex w-full flex-col gap-4 rounded-4xl border border-[#213126]/10 bg-white/60 p-3 shadow-xl shadow-[#213126]/5 backdrop-blur md:flex-row md:items-center md:justify-between" aria-label="{{ __('Main navigation') }}">
            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-2xl px-2 py-1 transition hover:bg-white/55 focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">
                        <span class="grid size-12 place-items-center rounded-2xl bg-[#17231a] text-sm font-black uppercase tracking-[0.12em] text-[#f8c76b] shadow-lg">
                            <img src="https://www.google.nl/url?sa=t&source=web&rct=j&url=https%3A%2F%2Fnl.pinterest.com%2Fvuurvliegjes1947%2F&ved=0CBYQjRxqFwoTCPCyle3X5ZQDFQAAAAAdAAAAABBr&opi=89978449" alt="Logo" class="h-6 w-6 object-contain">
                        </span>
                <span class="leading-tight">
                            <span class="block text-sm font-black uppercase tracking-[0.22em] text-[#6f4b25]">{{ __('Camping') }}</span>
                            <span class="block text-lg font-black text-[#17231a]">De Vuurvlieg</span>
                        </span>
            </a>

            <div class="flex flex-wrap items-center gap-2 text-sm font-black text-[#213126]">
                <a href="/" class="rounded-full px-4 py-2 transition hover:bg-[#17231a] hover:text-white focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">{{ __('Home') }}</a>
                <a href="/booking" class="rounded-full px-4 py-2 transition hover:bg-[#17231a] hover:text-white focus:outline-none focus:ring-4 focus:ring-[#264f3a]/15">{{ __('Book') }}</a>
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
                    <p class="text-sm font-bold uppercase tracking-[0.35em] text-[#6f4b25]"></p>
                    <h1 class="max-w-3xl text-5xl font-black leading-[0.95] tracking-tight text-[#17231a] sm:text-6xl lg:text-7xl">

                    </h1>
                    <p class="max-w-xl text-lg leading-8 text-[#415143]">

                    </p>
                </div>
            </div>




            <form></form>

                <div class="mb-6">
                    <p class="text-sm font-bold uppercase tracking-[0.3em] text-[#f2d29f]"></p>
                    <h2 class="mt-2 text-3xl font-black"></h2>
                </div>

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl bg-[#f8c76b] p-4 text-sm font-semibold text-[#221407]">

                    </div>
                @endif

        </div>
    </section>
</main>
</body>
</html>
