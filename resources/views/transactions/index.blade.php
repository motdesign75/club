@extends('layouts.sidebar')

@section('title', 'Buchungen')

@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-gray-800">🧾 Buchungsübersicht</h1>

        <div class="flex justify-between items-center">
            <p class="text-sm text-gray-600">
                Alle Buchungen im Überblick
            </p>
            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                ➕ Neue Buchung
            </a>
        </div>

        <div class="overflow-auto bg-white rounded shadow ring-1 ring-gray-200">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase">
                    <tr>
                        <th class="px-4 py-3">Datum</th>
                        <th class="px-4 py-3">Beschreibung</th>
                        <th class="px-4 py-3">Von-Konto</th>
                        <th class="px-4 py-3">Nach-Konto</th>
                        <th class="px-4 py-3 text-right">Betrag</th>
                        <th class="px-4 py-3">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50 transition">
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($transaction->date)->format('d.m.Y') }}</td>
                            <td class="px-4 py-3">{{ $transaction->description }}</td>
                            <td class="px-4 py-3">{{ $transaction->account_from->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $transaction->account_to->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right font-mono">{{ number_format($transaction->amount, 2, ',', '.') }} €</td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="{{ route('transactions.edit', $transaction) }}"
                                   class="text-blue-600 hover:underline">Bearbeiten</a>

                                <form action="{{ route('transactions.destroy', $transaction) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Buchung wirklich löschen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Löschen</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                Keine Buchungen vorhanden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
