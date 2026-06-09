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
    <body class="bg-no-repeat bg-cover bg-fixed bg-[radial-gradient(circle_at_20%_20%,#f8c76b_0,transparent_60%),linear-gradient(135deg,#fff7e4_0%,#d8ead8_100%)]
">

    <header class="w-full px-4 pt-4 sm:px-6 lg:px-8">
        <nav class="flex w-full flex-col gap-4 rounded-4xl border border-[#213126]/10 bg-white/60 p-3 shadow-xl shadow-[#213126]/5 backdrop-blur md:flex-row md:items-center md:justify-between" aria-label="{{ __('Main navigation') }}">

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
                <section>
                <span class="ml-320 flex items-center gap-4">
                        <button
                            onclick="window.location.href='/login'"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-full">
                            {{ __('Uitloggen') }}
                        </button>
                </span>
                </section>
            </div>
        </nav>
    </header>

        <main class=" px-6 py-12">
            <section class="w-full max-w-2xl rounded-4xl bg-white/75 p-8 shadow-2xl ring-1 ring-[#213126]/10 backdrop-blur md:p-10">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-[#6f4b25]">{{ __('Dashboard') }}</p>
                <h1 class="mt-3 text-4xl font-black tracking-tight text-[#17231a] md:text-5xl">
                    {{ __('Hallo, Admin!') }}
                </h1>

                <div class="mt-8 rounded-3xl bg-[#17231a] p-6 text-white">
                    <p class="text-lg font-bold">{{ __('Your bookings') }}</p>
{{--                    @foreach($reserveringen as $res)--}}
{{--                        <li class="p-4 bg-white/20 rounded-xl">--}}
{{--                            <p>Property: {{ $res->property_id }}</p>--}}
{{--                            <p>Start: {{ $res->start_date }}</p>--}}
{{--                            <p>Eind: {{ $res->end_date }}</p>--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
                </div>
            </section>

            <section class="w-full max-w-2xl rounded-4xl bg-white/75 p-8 shadow-2xl ring-1 ring-[#213126]/10 backdrop-blur md:p-10 mt-12">
                <h1 class="mt-3 text-4xl font-black tracking-tight text-[#17231a] md:text-5xl">
                    {{ __('Customers') }}
                </h1>
                <div class="mt-8 rounded-3xl bg-[#17231a] p-6 text-white">
                    <p>{{ __('') }}</p>
                </div>
            </section>
            <section class="w-full max-w-2xl rounded-4xl bg-white/75 p-8 shadow-2xl ring-1 ring-[#213126]/10 backdrop-blur md:p-10 mt-12">
                <h1 class="mt-3 text-4xl font-black tracking-tight text-[#17231a] md:text-5xl">
                    {{ __('') }}
                </h1>
                <div class="mt-8 rounded-3xl bg-[#17231a] p-6 text-white">
                    <p>{{ __('') }}</p>
                </div>
            </section>
        </main>
    </body>
</html>
