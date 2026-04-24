@extends('layouts.app')

@section('title', 'Clubano Lizenz')

@section('content')
<div class="max-w-2xl mx-auto py-12 text-center">

    <h1 class="text-3xl font-bold mb-4">
        Ein Preis. Alles drin.
    </h1>

    <p class="text-gray-600 mb-8">
        Keine versteckten Kosten. Keine komplizierten Tarife.
    </p>

    <form method="POST" action="{{ route('subscription.checkout') }}">
        @csrf

        {{-- 🔥 DEINE STRIPE PRICE ID --}}
        <input type="hidden" name="price_id" value="price_1TMm3iLTnGBaGb0l8O7P19vr">

        <div class="bg-white p-8 rounded-3xl shadow-lg">

            <h2 class="text-2xl font-semibold mb-2">Clubano</h2>

            <p class="text-4xl font-bold mb-6">
                19,99 € <span class="text-lg font-normal">/ Monat</span>
            </p>

            <div class="text-left space-y-2 text-gray-700 mb-6">
                <p>✔ Mitgliederverwaltung</p>
                <p>✔ Finanzen & Beiträge</p>
                <p>✔ Protokolle & Events</p>
                <p>✔ DSGVO-Export</p>
                <p>✔ Nutzerrollen</p>
                <p>✔ Import & Export</p>
            </div>

            <button class="w-full bg-blue-600 text-white py-3 rounded-xl text-lg hover:bg-blue-700">
                Jetzt kostenlos starten
            </button>

            <p class="text-xs text-gray-500 mt-4">
                7 Tage kostenlos testen · Keine Kündigungsfrist
            </p>

        </div>

    </form>

</div>
@endsection