@extends('layouts.sidebar')

@section('title', 'Mitglied: ' . $member->first_name . ' ' . $member->last_name)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <h1 class="text-2xl font-bold text-gray-800">
        👤 Mitgliederdetails: {{ $member->salutation }} {{ $member->first_name }} {{ $member->last_name }}
    </h1>

    {{-- Block: Mitglied --}}
    <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-blue-500">
        <h2 class="text-lg font-semibold text-blue-600 mb-4">🔹 Mitglied</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <div><strong>Geschlecht:</strong> {{ $member->gender }}</div>
            <div><strong>Anrede:</strong> {{ $member->salutation }}</div>
            <div><strong>Titel:</strong> {{ $member->title }}</div>
            <div><strong>Vorname:</strong> {{ $member->first_name }}</div>
            <div><strong>Nachname:</strong> {{ $member->last_name }}</div>
            <div><strong>Firma / Organisation:</strong> {{ $member->company }}</div>
            <div><strong>Geburtstag:</strong> {{ $member->birthday }}</div>
        </div>
    </div>

    {{-- Block: Mitgliedschaft --}}
    <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-green-500">
        <h2 class="text-lg font-semibold text-green-600 mb-4">📅 Mitgliedschaft</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <div><strong>Mitgliedsnummer:</strong> {{ $member->member_id }}</div>
            <div><strong>Eintritt:</strong> {{ $member->entry_date }}</div>
            <div><strong>Austritt:</strong> {{ $member->exit_date }}</div>
            <div><strong>Kündigungsdatum:</strong> {{ $member->termination_date }}</div>
        </div>
    </div>

    {{-- Block: Kommunikation --}}
    <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-yellow-500">
        <h2 class="text-lg font-semibold text-yellow-600 mb-4">📞 Kommunikation</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <div><strong>E-Mail:</strong> {{ $member->email }}</div>
            <div><strong>Mobil:</strong> {{ $member->mobile }}</div>
            <div><strong>Festnetz:</strong> {{ $member->landline }}</div>
        </div>
    </div>

    {{-- Block: Adresse --}}
    <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-purple-500">
        <h2 class="text-lg font-semibold text-purple-600 mb-4">🏠 Adresse</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <div><strong>Straße + Nr.:</strong> {{ $member->street }}</div>
            <div><strong>Adresszusatz:</strong> {{ $member->address_addition }}</div>
            <div><strong>PLZ:</strong> {{ $member->zip }}</div>
            <div><strong>Ort:</strong> {{ $member->city }}</div>
            <div><strong>Land:</strong> {{ $member->country }}</div>
            <div><strong>C/O:</strong> {{ $member->care_of }}</div>
        </div>
    </div>

    <div class="flex justify-between pt-6">
        <a href="{{ route('members.index') }}" class="text-gray-600 hover:text-gray-900">← Zur Übersicht</a>
        <a href="{{ route('members.edit', $member) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ✏️ Bearbeiten
        </a>
    </div>
</div>
@endsection
