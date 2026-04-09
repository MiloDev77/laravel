<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>{{ $titleweb ?? 'France Product' }}</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">
    <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('public.products') }}" class="text-3xl font-bold text-blue-700 tracking-tight">
                🇫🇷 France Products
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm text-gray-600">
                <a href="{{ route('public.products') }}"
                    class="text-base font-semibold hover:text-blue-600 transition {{ request()->routeIs('public.products') ? 'text-blue-400' : '' }}">Products</a>
                <a href="{{ route('gtin.show') }}"
                    class="text-base font-semibold hover:text-blue-600 transition {{ request()->routeIs('gtin.show') ? 'text-blue-400' : '' }}">GTIN
                    Validator</a>
            </nav>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        <img src="/storage/{{ auth()->user()->avatar_path ?? 'default/user.png' }}" alt="Photo Profile"
                            class="w-8 h-8 rounded-full object-cover border border-gray-300">
                    </a>
                    {{-- <span class="text-md font-medium text-gray-700">{{ auth()->user()->name }}</span> --}}
                    <form method="POST" action="{{ route('user.logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium bg-red-500 text-white rounded-2xl px-4 py-2 hover:text-red-700 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('user.login') }}"
                        class="text-sm font-medium text-gray-600 hover:text-blue-600 transition">Sign
                        In</a>
                    <a href="{{ route('register') }}"
                        class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded-lg
                              hover:bg-blue-700 transition">Register</a>
                @endauth
            </div>
        </div>
    </header>
    <div class="max-w-6xl mx-auto px-4 w-full">
        <x-alert></x-alert>
    </div>

    <main class="bg-white border-t border-gray-200 text-center text-xs text-gray-400 py-4">
        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-gray-200 text-center text-xs text-gray-400 py-4">
        France Products Management System &copy; 2026
    </footer>
</body>

</html>
