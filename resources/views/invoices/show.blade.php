<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            📄 Rechnung #{{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 bg-white p-8 rounded-xl shadow-lg border border-gray-200 space-y-6">
        
        {{-- Rechnungsdetails --}}
        <div class="space-y-2 text-gray-800">
            <div class="text-lg font-semibold text-indigo-700">
                👤 {{ $invoice->member->full_name }}
            </div>
            <p><strong>📅 Datum:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}</p>
            <p><strong>💰 Betrag:</strong> 
                <span class="text-green-600 font-bold text-lg">
                    {{ number_format($invoice->amount, 2, ',', '.') }} €
                </span>
            </p>
            <p><strong>📌 Status:</strong> 
                <span class="inline-block px-2 py-1 rounded text-xs
                    {{ $invoice->status === 'bezahlt' ? 'bg-green-100 text-green-800' :
                       ($invoice->status === 'versendet' ? 'bg-yellow-100 text-yellow-800' :
                       ($invoice->status === 'storniert' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </p>
            @if($invoice->description)
                <p><strong>📝 Beschreibung:</strong> {{ $invoice->description }}</p>
            @endif
        </div>

        {{-- PDF Button --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('invoices.pdf', $invoice) }}"
               target="_blank"
               class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700 transition">
                📥 PDF anzeigen
            </a>

            <a href="{{ route('invoices.index') }}"
               class="text-gray-600 hover:text-indigo-600 underline text-sm">
                🔙 Zurück zur Übersicht
            </a>
        </div>

        {{-- Vorschau-Platzhalter --}}
        <div class="mt-4">
            <h3 class="text-md font-semibold text-gray-700 mb-2">🖨️ Vorschau (bald verfügbar)</h3>
            <div class="border-2 border-dashed border-gray-300 rounded p-6 text-center text-gray-500 bg-gray-50">
                <em>Die PDF-Vorschau wird hier bald eingebettet.</em>
            </div>
        </div>
    </div>
</x-app-layout>
