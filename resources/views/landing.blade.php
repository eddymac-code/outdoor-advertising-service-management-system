<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env('APP_NAME') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css'])

    </head>
    <body class="m-0 h-full landing-body">
        <header class="fixed top-0 left-0 w-full z-50">
            <nav
                id="navbar" 
                class="flex justify-end gap-4 px-8 py-6 bg-transparent">
                <a href="{{ route('login') }}"
                class="text-white font-medium hover:underline">
                    Login
                </a>

                <a href="{{ route('register') }}"
                class="text-white font-medium hover:underline">
                    Register
                </a>
            </nav>
        </header>
        <section class="welcome-img">
            <div class="welcome-text">
                <h1 class="text-7xl uppercase">This is <span class="text-orange-500">OA</span>SMS</h1>
                <p class="text-2xl">Seamless Outdoor Advertising Management</p>
                {{-- <button class="text-center px-3 py-2 border-3 bg-transparent mt-5 hover:bg-orange-500 cursor-pointer transition-all duration-300 ease-out">
                    <span>View Catalog</span>
                </button> --}}
                <button
                    class="relative mt-6 px-6 py-3 border-2 border-orange-500
                        text-white font-medium
                        transition-all duration-300 ease-in-out
                        hover:bg-orange-500 hover:-translate-y-1 hover:shadow-lg"
                    onclick="document.getElementById('catalog').scrollIntoView({ behavior: 'smooth' })"
                >
                    View Catalog
                </button>
            </div>
            <div class="welcome-credit-text">
                Photo by <a href="https://unsplash.com/@charliedeets?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Charlie Deets</a> on <a href="https://unsplash.com/photos/white-and-yellow-building-near-trees-during-daytime-fSF9Ymg6SlA?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Unsplash</a>
                {{-- Photo by <a href="https://unsplash.com/@bramnaus?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Bram Naus</a> on <a href="https://unsplash.com/photos/black-and-white-street-sign-5fvMWp1r2n0?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Unsplash</a> --}}
            </div>
        </section>
        <section id="catalog" class="min-h-screen">
            {{-- Asset catalogue here --}}
            <h2 class="text-center font-semibold pt-20">Catalogue</h2>
            <div class="grid grid-cols-3 gap-8 sm:grid-cols-3 pt-8 px-12">
                @foreach ($assets as $asset)
                    <x-card shadow="lg" rounded="lg">
                        <div class="flex items-center justify-between p-4 border-b border-gray-200 hover:bg-gray-50 transition">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    {{-- <x-avatar src="{{ asset('storage/'.$asset->image) }}" size="xl" rounded="xl" /> --}}
                                    <img src="{{ asset('storage/'.$asset->image) }}" class="w-24 h-24 rounded-xl" />
                                </div>
                            
                                <div class="flex-1 space-x-4 min-w-0">
                                    <p class="text-[0.9rem] font-semibold text-slate-900">Name: {{ $asset->name }}</p>
                                    <p class="text-sm text-slate-500">Type: {{ Str::ucwords($asset->type) }} {{ ($asset->size) }}</p>
                                    <p><i class="fa-solid fa-location-dot"></i> {{ $asset->location }}</p>
                                    <p>Price Per Month: {{ $asset->price_per_month }}</p>

                                    <x-badge flat primary label="{{ Str::ucwords($asset->status) }}" class="mt-2">
                                        <x-slot name="append" class="relative flex items-center w-2 h-2">
                                            <span
                                                class="absolute inline-flex w-full h-full rounded-full opacity-75 bg-emerald-500 animate-ping"></span>

                                            <span class="relative inline-flex w-2 h-2 rounded-full bg-emerald-500"></span>
                                        </x-slot>
                                    </x-badge>
                                </div>
                            </div>
                        </div>
                    </x-card>    
                @endforeach
            </div>
        </section>
    </body>
    <script>
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', () => {
            if (window.scrollY >= window.innerHeight) {
                navbar.classList.add('nav-scrolled');
            } else {
                navbar.classList.remove('nav-scrolled');
            }
        });
    </script>
</html>

