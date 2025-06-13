@extends('layouts.sidebar')

@section('title', 'Profil')

@section('content')
<div class="max-w-4xl mx-auto space-y-10 text-gray-800">
    <h1 class="text-2xl font-bold mb-4">🙍 Profil bearbeiten</h1>

    {{-- Profilinformationen --}}
    <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-400">
        <h2 class="text-lg font-semibold text-blue-700 mb-2">📋 Persönliche Daten</h2>
        <p class="text-sm text-gray-600 mb-4">Aktualisiere deinen Namen und deine E-Mail-Adresse.</p>

        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </section>

    {{-- Passwort ändern --}}
    <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-400">
        <h2 class="text-lg font-semibold text-yellow-700 mb-2">🔐 Passwort ändern</h2>
        <p class="text-sm text-gray-600 mb-4">Verwende ein sicheres Passwort für deinen Zugang.</p>

        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </section>

    {{-- Account löschen --}}
    <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
        <h2 class="text-lg font-semibold text-red-700 mb-2">🗑️ Account löschen</h2>
        <p class="text-sm text-gray-600 mb-4">⚠️ Hinweis: Das Löschen deines Benutzerkontos ist endgültig. Alle zugehörigen Daten werden unwiderruflich entfernt und können nicht wiederhergestellt werden.</p>

        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </section>
</div>
@endsection