<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Inloggen</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
    <body class="min-h-screen bg-gradient-to-b from-[#f6f0e3] to-[#e9f2ea] text-[#213126] antialiased">
    <main id="login" class="min-h-screen flex items-center justify-center px-6">
        <div class="relative w-full max-w-lg">
            <div class="absolute -inset-6 blur-xl opacity-30 bg-[radial-gradient(circle_at_10%_20%,#f8c76b_0,transparent_20%),radial-gradient(circle_at_90%_10%,#6fa28b_0,transparent_20%)] rounded-3xl"></div>

            <div class="relative bg-white/90 backdrop-blur rounded-3xl shadow-2xl p-8 md:p-12">
                <div class="flex justify-center mb-6">
                <img src="{{ asset('images/vuurvlieg.jpg') }}" alt="Logo" class="h-12 w-12 object-contain rounded-full shadow" />
                </div>

            <h1 class="text-2xl md:text-3xl font-black text-center text-[#17231a] mb-6">{{ __('Inloggen') }}</h1>

            @if(session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-[#213126] mb-2">{{ __('E-mail') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#264f3a] focus:border-transparent" />
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-[#213126] mb-2">{{ __('Wachtwoord') }}</label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#264f3a] focus:border-transparent" />
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-[#213126]">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded" />
                        <span>{{ __('Onthoud mij') }}</span>
                    </label>

                </div>

                <button type="submit" class="w-full mt-1 bg-[#264f3a] text-white font-semibold py-3 rounded-lg hover:bg-[#17231a] transition-shadow shadow">{{ __('Inloggen') }}</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
