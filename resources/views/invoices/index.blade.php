<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">Beitragsrechnungen</h2>
    </x-slot>

    <div class="p-6 space-y-4">

        {{-- Erfolgsmeldung --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Button zum Erstellen --}}
        <div class="flex justify-end">
            <a href="{{ route('invoices.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ➕ Neue Rechnung
            </a>
        </div>

        {{-- Tabelle --}}
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 shadow rounded">
                <thead class="bg-gray-100 text-sm">
                    <tr>
                        <th class="px-4 py-2 text-left">Rechnungsnummer</th>
                        <th class="px-4 py-2 text-left">Mitglied</th>
                        <th class="px-4 py-2 text-left">Datum</th>
                        <th class="px-4 py-2 text-right">Betrag</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-center">Aktionen</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($invoices as $invoice)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $invoice->invoice_number }}</td>
                            <td class="px-4 py-2">
                                {{ $invoice->member->last_name }}, {{ $invoice->member->first_name }}
                            </td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                {{ number_format($invoice->getTotal(), 2, ',', '.') }} €
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-block px-2 py-1 rounded text-xs
                                    {{ $invoice->status === 'bezahlt' ? 'bg-green-100 text-green-800' :
                                       ($invoice->status === 'versendet' ? 'bg-yellow-100 text-yellow-800' :
                                       ($invoice->status === 'storniert' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <a href="{{ route('invoices.show', $invoice) }}"
                                   class="text-blue-600 hover:underline">
                                    Details
                                </a>
                                <a href="{{ route('invoices.pdf', $invoice) }}"
                                   class="text-blue-600 hover:underline"
                                   target="_blank">
                                    PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                Noch keine Rechnungen vorhanden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
