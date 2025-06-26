@extends('layouts.sidebar')

@section('title', 'Vereinsprofil')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold text-gray-800">🏢 Vereinsprofil</h1>

        <div class="bg-white p-6 rounded shadow space-y-4">
            <p><strong>Name:</strong> {{ $tenant->name }}</p>
            <p><strong>E-Mail:</strong> {{ $tenant->email }}</p>
            <p><strong>Adresse:</strong> {{ $tenant->address }}, {{ $tenant->zip }} {{ $tenant->city }}</p>
            <p><strong>Telefon:</strong> {{ $tenant->phone }}</p>
            <p><strong>Registernummer:</strong> {{ $tenant->register_number }}</p>

            {{-- Neue Bankdaten --}}
            @if($tenant->iban)
                <p><strong>IBAN:</strong> {{ $tenant->iban }}</p>
            @endif

            @if($tenant->bic)
                <p><strong>BIC:</strong> {{ $tenant->bic }}</p>
            @endif

            @if($tenant->bank_name)
                <p><strong>Bankname:</strong> {{ $tenant->bank_name }}</p>
            @endif

            @if($tenant->chairman_name)
                <p><strong>Vorsitzende/r:</strong> {{ $tenant->chairman_name }}</p>
            @endif

            {{-- Logo --}}
            @if ($tenant->logo)
                <div>
                    <strong>Logo:</strong><br>
                    <img src="{{ Storage::url($tenant->logo) }}" alt="Vereinslogo" class="h-24 rounded shadow mt-2">
                </div>
            @endif

            {{-- Briefbogen --}}
            @if ($tenant->pdf_template)
                <p>
                    <strong>Briefbogen (PDF):</strong>
                    <a href="{{ Storage::url($tenant->pdf_template) }}" target="_blank" class="text-blue-600 hover:underline ml-2">
                        📄 Anzeigen / Download
                    </a>
                </p>
            @endif
        </div>

        <div class="text-right">
            <a href="{{ route('tenant.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ✏️ Bearbeiten
            </a>
        </div>
    </div>
@endsection
