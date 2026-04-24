@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            📄 Rechnung #{{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto mt-6 bg-white p-8 rounded-xl shadow-lg border border-gray-200 space-y-6">

        {{-- Rechnungsdetails --}}
        <div class="space-y-2 text-gray-800">
            <div class="text-lg font-semibold text-indigo-700">
                👤 {{ $invoice->member->full_name }}
            </div>

            <p>
                <strong>📅 Datum:</strong>
                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}
            </p>

            <p>
                <strong>💰 Betrag:</strong>
                <span class="text-green-600 font-bold text-lg">
                    {{ number_format($invoice->amount, 2, ',', '.') }} €
                </span>
            </p>

            <p>
                <strong>📌 Status:</strong>
                <span class="inline-block px-2 py-1 rounded text-xs
                    {{ $invoice->status === 'bezahlt' ? 'bg-green-100 text-green-800' :
                       ($invoice->status === 'versendet' ? 'bg-yellow-100 text-yellow-800' :
                       ($invoice->status === 'storniert' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </p>

            @if($invoice->description)
                <p>
                    <strong>📝 Beschreibung:</strong>
                    {{ $invoice->description }}
                </p>
            @endif
        </div>

        {{-- Platzhalter für Seitenleiste beachten --}}

        {{-- PDF Aktionen --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('invoices.pdf', $invoice) }}"
               target="_blank"
               rel="noopener"
               class="inline-flex items-center justify-center gap-2 bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700 transition">
                📥 PDF in neuem Tab öffnen
            </a>

            <a href="{{ route('invoices.index') }}"
               class="text-gray-600 hover:text-indigo-600 underline text-sm">
                🔙 Zurück zur Übersicht
            </a>
        </div>

        {{-- PDF Vorschau --}}
        <div class="mt-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-2">
                <h3 class="text-md font-semibold text-gray-700">
                    🖨️ PDF-Vorschau
                </h3>

                <a href="{{ route('invoices.pdf', $invoice) }}"
                   target="_blank"
                   rel="noopener"
                   class="text-sm text-indigo-600 hover:text-indigo-800 underline">
                    Vorschau separat öffnen
                </a>
            </div>

            <div class="border border-gray-300 rounded-lg overflow-hidden bg-gray-50">
                <iframe
                    src="{{ route('invoices.pdf', $invoice) }}#toolbar=1&navpanes=0&scrollbar=1"
                    class="w-full h-[800px]"
                    title="PDF-Vorschau Rechnung {{ $invoice->invoice_number }}">
                </iframe>
            </div>

            <p class="mt-2 text-xs text-gray-500">
                Falls die Vorschau im Browser nicht angezeigt wird, öffne die PDF über den Button oben in einem neuen Tab.
            </p>
        </div>
    </div>
@endsection
