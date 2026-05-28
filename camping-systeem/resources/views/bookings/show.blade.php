<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Boeking bevestigd</title>

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-[#f3ead8] text-[#213126] antialiased">
        <main class="grid min-h-screen place-items-center bg-[radial-gradient(circle_at_20%_20%,#f8c76b_0,transparent_26%),linear-gradient(135deg,#fff7e4_0%,#d8ead8_100%)] px-6 py-12">
            <section class="w-full max-w-2xl rounded-[2rem] bg-white/75 p-8 shadow-2xl ring-1 ring-[#213126]/10 backdrop-blur md:p-10">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-[#6f4b25]">Boeking bevestigd</p>
                <h1 class="mt-3 text-4xl font-black tracking-tight text-[#17231a] md:text-5xl">
                    Je plek is vastgelegd.
                </h1>

                <div class="mt-8 rounded-3xl bg-[#17231a] p-6 text-white">
                    <p class="text-lg font-bold">{{ $booking->guest_name }}</p>
                    <p class="mt-2 text-white/75">
                        {{ $booking->campingSpot->name }} van {{ $booking->start_date->toDateString() }}
                        tot {{ $booking->end_date->toDateString() }}.
                    </p>
                </div>

                <a class="mt-8 inline-flex rounded-2xl bg-[#f8c76b] px-5 py-3 text-sm font-black uppercase tracking-[0.2em] text-[#17231a] transition hover:-translate-y-0.5 hover:bg-[#ffd982]" href="{{ route('bookings.create') }}">
                    Nieuwe boeking
                </a>
            </section>
        </main>
    </body>
</html>
