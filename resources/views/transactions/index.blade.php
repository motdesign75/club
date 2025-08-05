@extends('layouts.app')

@section('title', '📒 Buchungen')

@section('content')
    @php
        $currentYear = now()->year;
        $selectedYear = request('year', $currentYear);
        $selectedMonth = request('month', '');
    @endphp

    <div class="space-y-8">
        {{-- Überschrift und Filter --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#2954A3]">🧾 Buchungsübersicht</h1>
                <p class="text-sm text-gray-500">Alle Einnahmen, Ausgaben und Stornos im Überblick.</p>
            </div>
            <div class="flex flex-wrap items-end gap-3">
                {{-- Filter-Knöpfe --}}
                <div class="flex gap-2">
                    <a href="{{ route('transactions.index') }}"
                       class="px-3 py-1 rounded-lg text-sm font-medium {{ $filter === null ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Alle
                    </a>
                    <a href="{{ route('transactions.index', ['filter' => 'income', 'year' => $selectedYear, 'month' => $selectedMonth]) }}"
                       class="px-3 py-1 rounded-lg text-sm font-medium {{ $filter === 'income' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        📥 Einnahmen
                    </a>
                    <a href="{{ route('transactions.index', ['filter' => 'expense', 'year' => $selectedYear, 'month' => $selectedMonth]) }}"
                       class="px-3 py-1 rounded-lg text-sm font-medium {{ $filter === 'expense' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        📤 Ausgaben
                    </a>
                    <a href="{{ route('transactions.index', ['filter' => 'storno', 'year' => $selectedYear, 'month' => $selectedMonth]) }}"
                       class="px-3 py-1 rounded-lg text-sm font-medium {{ $filter === 'storno' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        ♻️ Stornos
                    </a>
                </div>

                {{-- Jahr Filter --}}
                <div>
                    <label for="year" class="block text-sm text-gray-600 font-medium mb-1">Jahr</label>
                    <select id="year" name="year"
                            onchange="location.href='{{ route('transactions.index', ['filter' => $filter, 'month' => $selectedMonth]) }}&year=' + this.value"
                            class="rounded border-gray-300 text-sm shadow-sm">
                        @for($y = $currentYear; $y >= $currentYear - 10; $y--)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Monat Filter --}}
                <div>
                    <label for="month" class="block text-sm text-gray-600 font-medium mb-1">Monat</label>
                    <select id="month" name="month"
                            onchange="location.href='{{ route('transactions.index', ['filter' => $filter, 'year' => $selectedYear]) }}&month=' + this.value"
                            class="rounded border-gray-300 text-sm shadow-sm">
                        <option value="">– Alle –</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate($currentYear, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Neue Buchung --}}
                <a href="{{ route('transactions.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                    ➕ Neue Buchung
                </a>
            </div>
        </div>

        {{-- Buchungstabelle --}}
        <div class="overflow-x-auto bg-white rounded-xl shadow ring-1 ring-gray-200">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase">
                    <tr>
                        <th class="px-4 py-3">Datum</th>
                        <th class="px-4 py-3">Beleg-Nr.</th>
                        <th class="px-4 py-3">Beschreibung</th>
                        <th class="px-4 py-3">Von</th>
                        <th class="px-4 py-3">Nach</th>
                        <th class="px-4 py-3 text-right">Betrag</th>
                        <th class="px-4 py-3 text-center">Beleg</th>
                        <th class="px-4 py-3">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        @php
                            $icon = '🔄';
                            if (str_starts_with($transaction->description, 'Storno:')) {
                                $icon = '♻️';
                            } elseif(optional($transaction->account_to)->type === 'einnahme') {
                                $icon = '📥';
                            } elseif(optional($transaction->account_to)->type === 'ausgabe') {
                                $icon = '📤';
                            }

                            $amountClass = 'text-gray-800'; // Standard

                            if (str_starts_with($transaction->description, 'Storno:')) {
                                $amountClass = 'text-black';
                            } elseif(optional($transaction->account_to)->type === 'einnahme') {
                                $amountClass = 'text-green-600';
                            } elseif(optional($transaction->account_to)->type === 'ausgabe') {
                                $amountClass = 'text-red-600';
                            }
                        @endphp
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50 transition">
                            <td class="px-4 py-3 font-mono text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($transaction->date)->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">
                                {{ $transaction->receipt_number ?? '–' }}
                            </td>
                            <td class="px-4 py-3 flex items-center gap-2">
                                <span class="text-xl">{{ $icon }}</span>
                                <span>{{ $transaction->description }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-800">{{ $transaction->account_from->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $transaction->account_to->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right font-mono {{ $amountClass }}">
                                {{ number_format($transaction->amount, 2, ',', '.') }} €
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($transaction->receipt_file)
                                    <a href="{{ route('receipts.show', $transaction->receipt_file) }}"
                                       target="_blank" title="Beleg ansehen"
                                       class="text-blue-600 hover:text-blue-800 transition text-lg">
                                        📎
                                    </a>
                                @else
                                    <span class="text-gray-400">–</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if (!str_starts_with($transaction->description, 'Storno:'))
                                    <a href="{{ route('transactions.cancel', $transaction) }}"
                                       onclick="return confirm('Buchung wirklich stornieren? Du wirst eine Begründung eingeben müssen.');"
                                       class="text-red-600 hover:underline text-sm">
                                       Stornieren
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Storniert</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                Keine Buchungen vorhanden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
