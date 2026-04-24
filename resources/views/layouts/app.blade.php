<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ open: false }" x-cloak class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Clubano'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script src="{{ asset('vendor/livewire/livewire.js') }}"
            data-turbo-eval="false"
            data-csrf="{{ csrf_token() }}"></script>

    @stack('head')
</head>

<body class="h-full antialiased font-sans text-gray-800">

<!-- Mobiler Header -->
<header class="sm:hidden fixed top-0 left-0 right-0 z-40 bg-white shadow-md px-4 py-3 flex justify-between items-center">
    <div class="text-xl font-semibold">Clubano</div>

    <button @click="open = !open" aria-label="Menü öffnen">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
</header>

<!-- Mobile Sidebar -->
<div x-show="open"
     x-transition
     class="sm:hidden fixed top-0 left-0 z-50 w-64 h-full bg-white shadow-lg overflow-y-auto"
     x-cloak>

    <div class="relative h-full">
        <button @click="open = false" class="absolute top-3 right-3 text-gray-600">✕</button>

        <div class="p-4 mt-10">
            @include('layouts.sidebar')
        </div>
    </div>
</div>

<!-- Overlay -->
<div x-show="open"
     class="sm:hidden fixed inset-0 bg-black bg-opacity-25 z-40"
     x-cloak
     @click="open = false">
</div>

<!-- ❗ WICHTIG: overflow-hidden entfernt -->
<div class="flex h-screen">

    <!-- Sidebar Desktop -->
    <aside class="hidden sm:flex sm:flex-col sm:w-64 bg-white border-r border-indigo-100 shadow z-30 overflow-y-auto">
        @include('layouts.sidebar')
    </aside>

    <!-- Content -->
    <main class="flex-1 w-full overflow-y-auto sm:pt-6 px-4 pt-[56px]">
        @yield('content')
    </main>

</div>

{{-- ================= FEEDBACK ================= --}}
@auth

<!-- Button (stabil + sichtbar) -->
<button id="feedbackToggle"
    style="position: fixed; bottom: 20px; right: 20px; z-index: 99999;"
    class="bg-[#2954A3] text-white rounded-full shadow-lg flex items-center justify-center">

    <!-- Mobile -->
    <div class="w-12 h-12 flex items-center justify-center sm:hidden">
        💬
    </div>

    <!-- Desktop -->
    <div class="hidden sm:flex px-4 py-2">
        🗣️ Feedback
    </div>

</button>

<!-- Modal -->
<div id="feedbackModal"
     class="fixed inset-0 bg-black bg-opacity-40 hidden z-[99999] flex items-center justify-center p-4">

    <div class="bg-white p-6 rounded-xl w-full max-w-lg relative shadow-xl">

        <button onclick="closeFeedback()"
                class="absolute top-2 right-3 text-gray-500 text-xl">
            ×
        </button>

        <h2 class="text-xl font-bold mb-4">
            Feedback
        </h2>

        <form method="POST" action="{{ route('feedback.store') }}">
            @csrf

            <select name="category"
                    class="w-full border rounded p-2 mb-4">
                <option value="Fehler">Fehler</option>
                <option value="Verbesserung">Verbesserung</option>
                <option value="Allgemein">Allgemein</option>
            </select>

            <input type="hidden" name="view" value="{{ Route::currentRouteName() }}">
            <input type="hidden" name="url" value="{{ url()->full() }}">

            <textarea name="message"
                      required
                      rows="5"
                      class="w-full border rounded p-2"></textarea>

            <button type="submit"
                    class="mt-4 bg-[#2954A3] text-white px-4 py-2 rounded hover:opacity-90">
                Senden
            </button>
        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const toggleBtn = document.getElementById('feedbackToggle');
    const modal = document.getElementById('feedbackModal');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });
    }

    window.closeFeedback = function () {
        modal.classList.add('hidden');
    }

});
</script>

@endauth

@livewireScripts
@stack('scripts')

</body>
</html>