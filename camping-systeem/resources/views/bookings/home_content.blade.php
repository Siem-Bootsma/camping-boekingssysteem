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
<body class="min-h-screen bg-[#f3ead8] text-[#213126] antialiased">
<main id="login" class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white/80 backdrop-blur rounded-2xl shadow-lg p-8 mx-4">
        <h1 class="text-3xl font-black text-[#17231a] mb-8 text-center">{{ __('Inloggen') }}</h1>

        @if(session('status'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
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
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#264f3a] focus:border-transparent transition outline-none"
                />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-[#213126] mb-2">{{ __('Wachtwoord') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#264f3a] focus:border-transparent transition outline-none"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-[#213126]">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded cursor-pointer" />
                    <span>{{ __('Onthoud mij') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-[#264f3a] hover:text-[#17231a] font-semibold">
                        {{ __('Wachtwoord vergeten?') }}
                    </a>
                @endif
            </div>

            <button
                type="submit"
                class="w-full bg-[#264f3a] text-white font-semibold py-3 rounded-lg hover:bg-[#17231a] transition shadow-md focus:outline-none focus:ring-4 focus:ring-[#264f3a]/20"
            >
                {{ __('Inloggen') }}
            </button>
        </form>

    </div>
</main>
</body>
</html>
