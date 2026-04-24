@extends('layouts.app')

@section('title', 'Einnahmen & Ausgaben')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center">
        <div>
            <h1 class="text-2xl font-bold text-[#2954A3]">
                Einnahmen & Ausgaben
            </h1>

            <p class="text-sm text-gray-500">
                Finanzübersicht mit Buchungsliste
            </p>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Einnahmen --}}
        <div class="bg-green-600 text-white rounded-xl shadow p-6">
            <div class="text-sm opacity-80">Einnahmen gesamt</div>
            <div class="text-3xl font-bold mt-2">
                {{ number_format($totalIncome ?? 0, 2, ',', '.') }} €
            </div>
        </div>

        {{-- Ausgaben --}}
        <div class="bg-red-600 text-white rounded-xl shadow p-6">
            <div class="text-sm opacity-80">Ausgaben gesamt</div>
            <div class="text-3xl font-bold mt-2">
                {{ number_format($totalExpense ?? 0, 2, ',', '.') }} €
            </div>
        </div>

        {{-- Saldo --}}
        <div class="bg-[#2954A3] text-white rounded-xl shadow p-6">
            <div class="text-sm opacity-80">Saldo gesamt</div>
            <div class="text-3xl font-bold mt-2">
                {{ number_format($saldo ?? 0, 2, ',', '.') }} €
            </div>

            <div class="text-sm mt-3 opacity-80">
                Vergleich zum Vormonat derzeit nicht verfügbar
            </div>
        </div>

    </div>

    {{-- ZEITRAUM --}}
    <div class="text-sm text-gray-600">
        Zeitraum:
        <strong>{{ \Carbon\Carbon::parse($start)->format('d.m.Y') }}</strong>
        –
        <strong>{{ \Carbon\Carbon::parse($end)->format('d.m.Y') }}</strong>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b">
            <h2 class="font-semibold text-gray-800">
                Buchungen im Zeitraum
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">Datum</th>
                        <th class="p-3 text-left">Beschreibung</th>
                        <th class="p-3">Von</th>
                        <th class="p-3">Nach</th>
                        <th class="p-3 text-right">Betrag</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($transactions as $transaction)

                    @php
                        $amount = $transaction->amount;
                        $class = 'text-gray-800';
                        $prefix = '';

                        if (optional($transaction->account_from)->type === 'einnahme') {
                            $class = 'text-green-600 font-semibold';
                        } elseif (optional($transaction->account_to)->type === 'ausgabe') {
                            $class = 'text-red-600 font-semibold';
                            $prefix = '-';
                        } elseif (str_starts_with($transaction->description, 'Storno:')) {
                            $class = 'text-gray-500';
                        }
                    @endphp

                    <tr class="border-t hover:bg-blue-50">
                        <td class="p-3 font-mono">
                            {{ \Carbon\Carbon::parse($transaction->date)->format('d.m.Y') }}
                        </td>

                        <td class="p-3">
                            {{ $transaction->description }}
                        </td>

                        <td class="p-3">
                            {{ $transaction->account_from->name ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $transaction->account_to->name ?? '-' }}
                        </td>

                        <td class="p-3 text-right font-mono {{ $class }}">
                            {{ $prefix }}{{ number_format($amount, 2, ',', '.') }} €
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            Keine Buchungen gefunden
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection