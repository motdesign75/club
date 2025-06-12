@extends('layouts.sidebar')

@section('title', 'Mitglied: ' . $member->first_name . ' ' . $member->last_name)

@section('content')
<div class="max-w-5xl mx-auto space-y-8 text-gray-800">
    <h1 class="text-2xl font-bold">
        👤 Mitgliederdetails: {{ $member->salutation }} {{ $member->first_name }} {{ $member->last_name }}
    </h1>

    {{-- Block: Mitglied --}}
<section aria-labelledby="block-mitglied" class="bg-white shadow-md rounded-lg p-6 border-l-4 border-blue-500">
    <h2 id="block-mitglied" class="text-lg font-semibold text-blue-600 mb-4">🧍 Mitglied</h2>

    <div class="flex flex-col md:flex-row md:items-start gap-6">
        @if($member->photo)
            <div class="w-32 h-32 shrink-0">
                <img src="{{ asset('storage/' . $member->photo) }}"
                     alt="Profilfoto von {{ $member->first_name }} {{ $member->last_name }}"
                     class="w-32 h-32 object-cover rounded-full border shadow">
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
            <div><strong>Geschlecht:</strong> {{ $member->gender }}</div>
            <div><strong>Anrede:</strong> {{ $member->salutation }}</div>
            <div><strong>Titel:</strong> {{ $member->title }}</div>
            <div><strong>Vorname:</strong> {{ $member->first_name }}</div>
            <div><strong>Nachname:</strong> {{ $member->last_name }}</div>
            <div><strong>Firma / Organisation:</strong> {{ $member->organization }}</div>
            <div><strong>Geburtstag:</strong> {{ $member->birthday }}</div>
        </div>
    </div>
</section>

    {{-- Block: Mitgliedschaft --}}
    <section aria-labelledby="block-mitgliedschaft" class="bg-white shadow-md rounded-lg p-6 border-l-4 border-green-500">
        <h2 id="block-mitgliedschaft" class="text-lg font-semibold text-green-600 mb-4">📝 Mitgliedschaft</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <strong>Mitgliedschaft:</strong>
                @if($member->membership)
                    {{ $member->membership->name }} – {{ number_format($member->membership->fee, 2, ',', '.') }} € / {{ $member->membership->billing_cycle }}
                @else
                    –
                @endif
            </div>
            <div><strong>Mitgliedsnummer:</strong> {{ $member->member_id }}</div>
            <div><strong>Eintritt:</strong> {{ $member->entry_date }}</div>
            <div><strong>Austritt:</strong> {{ $member->exit_date }}</div>
            <div><strong>Kündigungsdatum:</strong> {{ $member->termination_date }}</div>
        </div>
    </section>

    {{-- Block: Kommunikation --}}
    <section aria-labelledby="block-kommunikation" class="bg-white shadow-md rounded-lg p-6 border-l-4 border-yellow-500">
        <h2 id="block-kommunikation" class="text-lg font-semibold text-yellow-600 mb-4">📞 Kommunikation</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>E-Mail:</strong> <a href="mailto:{{ $member->email }}" class="text-blue-600 underline">{{ $member->email }}</a></div>
            <div><strong>Mobil:</strong> <a href="tel:{{ $member->mobile }}" class="text-blue-600 underline">{{ $member->mobile }}</a></div>
            <div><strong>Festnetz:</strong> <a href="tel:{{ $member->landline }}" class="text-blue-600 underline">{{ $member->landline }}</a></div>
        </div>
    </section>

    {{-- Block: Adresse --}}
    <section aria-labelledby="block-adresse" class="bg-white shadow-md rounded-lg p-6 border-l-4 border-purple-500">
        <h2 id="block-adresse" class="text-lg font-semibold text-purple-600 mb-4">📍 Adresse</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Straße + Nr.:</strong> {{ $member->street }}</div>
            <div><strong>Adresszusatz:</strong> {{ $member->address_addition }}</div>
            <div><strong>PLZ:</strong> {{ $member->zip }}</div>
            <div><strong>Ort:</strong> {{ $member->city }}</div>
            <div><strong>Land:</strong>
                @php
                    $countryName = config('countries.list')[$member->country] ?? $member->country;
                @endphp
                {{ $countryName }}
            </div>
            <div><strong>C/O:</strong> {{ $member->care_of }}</div>
        </div>
    </section>

    {{-- Navigation --}}
    <nav class="flex justify-between pt-6 text-sm" aria-label="Aktionen">
        <a href="{{ route('members.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Zur Übersicht
        </a>
        <a href="{{ route('members.edit', $member) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            ✏️ Bearbeiten
        </a>
    </nav>
</div>
@endsection
