<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ open: false }" x-cloak class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Clubano'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles
    @stack('head')
</head>
<body class="h-full antialiased font-sans text-gray-800">

    <!-- Mobiler Header -->
    <header class="sm:hidden fixed top-0 left-0 right-0 z-40 bg-white shadow-md px-4 py-3 flex justify-between items-center">
        <div class="text-xl font-semibold">Clubano</div>
        <button @click="open = !open" aria-label="Menü öffnen">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </header>

    <!-- Mobile Sidebar -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform -translate-x-full"
         class="sm:hidden fixed top-0 left-0 z-50 w-64 h-full bg-white shadow-lg overflow-y-auto"
         x-cloak>
        <div class="relative h-full">
            <button @click="open = false" class="absolute top-3 right-3 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="p-4 mt-10">
                @include('layouts.sidebar')
            </div>
        </div>
    </div>

    <!-- Overlay bei mobilem Menü -->
    <div x-show="open"
         x-transition.opacity
         class="sm:hidden fixed inset-0 bg-black bg-opacity-25 z-40"
         x-cloak
         @click="open = false">
    </div>

    <!-- Desktop Layout -->
    <div class="flex">

        <!-- Sidebar (Desktop) -->
        <aside class="hidden sm:block fixed top-0 left-0 h-screen w-64 bg-white shadow z-30">
            @include('layouts.sidebar')
        </aside>

        <!-- Content -->
        <main class="flex-1 w-full sm:ml-64 pt-[56px] sm:pt-6 px-4">
            @yield('content')
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
