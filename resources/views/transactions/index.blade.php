@extends('layouts.app')

@section('title', 'Buchungen')

@section('content')

@php
    $currentYear = now()->year;
    $selectedYear = request('year', $currentYear);
    $selectedMonth = request('month', '');
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-[#2954A3]">
                Buchungen
            </h1>

            <p class="text-sm text-gray-500">
                Einnahmen, Ausgaben und Stornos im Überblick
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                ➕ Neue Buchung
            </a>

            <a href="{{ route('transactions.journal', [
                    'filter' => $filter,
                    'year' => $selectedYear,
                    'month' => $selectedMonth
                ]) }}"
               target="_blank"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg shadow hover:bg-black transition">
                🖨️ Buchungsjournal drucken
            </a>
        </div>

    </div>

    {{-- FILTER BOX --}}
    <div class="bg-white rounded-xl shadow p-4 md:p-6 space-y-4">

        <div class="flex flex-col md:flex-row md:items-end gap-4 flex-wrap">

            <div class="flex flex-wrap gap-2">

                <a href="{{ route('transactions.index') }}"
                   class="px-3 py-1 rounded-lg text-sm font-medium
                   {{ $filter === null ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Alle
                </a>

                <a href="{{ route('transactions.index', ['filter' => 'income', 'year' => $selectedYear, 'month' => $selectedMonth]) }}"
                   class="px-3 py-1 rounded-lg text-sm font-medium
                   {{ $filter === 'income' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📥 Einnahmen
                </a>

                <a href="{{ route('transactions.index', ['filter' => 'expense', 'year' => $selectedYear, 'month' => $selectedMonth]) }}"
                   class="px-3 py-1 rounded-lg text-sm font-medium
                   {{ $filter === 'expense' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📤 Ausgaben
                </a>

                <a href="{{ route('transactions.index', ['filter' => 'storno', 'year' => $selectedYear, 'month' => $selectedMonth]) }}"
                   class="px-3 py-1 rounded-lg text-sm font-medium
                   {{ $filter === 'storno' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    ♻️ Stornos
                </a>

            </div>

            {{-- Jahr --}}
            <div>
                <label class="text-sm text-gray-600">Jahr</label>
                <select
                    onchange="location.href='{{ route('transactions.index', ['filter' => $filter, 'month' => $selectedMonth]) }}&year=' + this.value"
                    class="border rounded-lg p-2 text-sm">
                    @for($y = $currentYear; $y >= $currentYear - 10; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Monat --}}
            <div>
                <label class="text-sm text-gray-600">Monat</label>
                <select
                    onchange="location.href='{{ route('transactions.index', ['filter' => $filter, 'year' => $selectedYear]) }}&month=' + this.value"
                    class="border rounded-lg p-2 text-sm">

                    <option value="">Alle</option>

                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate($currentYear, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach

                </select>
            </div>

        </div>

    </div>

    {{-- DESKTOP --}}
    <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
            <tr>
                <th class="p-3 text-left">Datum</th>
                <th class="p-3">Beleg</th>
                <th class="p-3 text-left">Beschreibung</th>
                <th class="p-3">Von</th>
                <th class="p-3">Nach</th>
                <th class="p-3 text-right">Betrag</th>
                <th class="p-3 text-center">Beleg</th>
                <th class="p-3">Aktion</th>
            </tr>
            </thead>

            <tbody>

            @forelse($transactions as $transaction)

                @php
                    $icon = '🔄';
                    $color = 'text-gray-800';
                    $prefix = '';

                    $isStorno = str_starts_with($transaction->description, 'Storno:');

                    if ($isStorno) {
                        $icon = '♻️';
                    } else {
                        if (in_array(optional($transaction->account_to)->type, ['bank', 'kasse'])) {
                            $icon = '📥';
                            $color = 'text-green-600 font-semibold';
                            $prefix = '+ ';
                        } elseif (in_array(optional($transaction->account_from)->type, ['bank', 'kasse'])) {
                            $icon = '📤';
                            $color = 'text-red-600 font-semibold';
                            $prefix = '- ';
                        }
                    }
                @endphp

                <tr class="border-t hover:bg-blue-50">

                    <td class="p-3 font-mono">
                        {{ \Carbon\Carbon::parse($transaction->date)->format('d.m.Y') }}
                    </td>

                    <td class="p-3 text-xs">
                        {{ $transaction->receipt_number ?? '-' }}
                    </td>

                    <td class="p-3 {{ $color }}">
                        {{ $icon }} {{ $transaction->description }}
                    </td>

                    <td class="p-3">
                        {{ $transaction->account_from->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $transaction->account_to->name ?? '-' }}
                    </td>

                    <td class="p-3 text-right font-mono {{ $color }}">
                        {{ $prefix }}{{ number_format($transaction->amount, 2, ',', '.') }} €
                    </td>

              <td class="p-3 text-center">
    @if($transaction->receipt_file)

        <div x-data="{ open: false }">

            <!-- Büroklammer -->
            <button @click="open = true"
                class="text-blue-600 text-lg hover:text-blue-800">
                📎
            </button>

            <!-- Modal -->
            <div x-show="open"
                 x-transition
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">

                <div class="bg-white w-full h-full md:h-[90vh] md:max-w-3xl md:rounded-2xl overflow-hidden flex flex-col">

                    <!-- Header -->
                    <div class="flex justify-between items-center p-4 border-b">
                        <span class="text-sm font-semibold text-gray-700">
                            Belegvorschau
                        </span>

                        <button @click="open = false"
                            class="text-gray-500 hover:text-black text-xl">
                            ✕
                        </button>
                    </div>

                    <!-- Content -->
<div class="flex-1 flex items-center justify-center bg-gray-100">

    @php
        $ext = strtolower(pathinfo($transaction->receipt_file, PATHINFO_EXTENSION));
        $url = route('receipts.show', $transaction->receipt_file);
    @endphp

    @if(in_array($ext, ['jpg','jpeg','png','webp']))
        <img src="{{ $url }}"
             class="max-w-full max-h-full object-contain">
             
    @elseif($ext === 'pdf')
        <iframe src="{{ $url }}#toolbar=1&navpanes=0&scrollbar=1"
                class="w-full h-full">
        </iframe>

    @else
        <p class="text-sm text-gray-500">
            Keine Vorschau verfügbar
        </p>
    @endif

</div>

                </div>
            </div>

        </div>

    @endif
</td>

                    <td class="p-3 space-x-2">
                        @if (!$isStorno)
                            <a href="{{ route('transactions.edit', $transaction) }}"
                               class="text-blue-600 text-sm">
                                ✏️ Bearbeiten
                            </a>

                            <a href="{{ route('transactions.cancel', $transaction) }}"
                               class="text-red-600 text-sm">
                                Stornieren
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">
                                Storniert
                            </span>
                        @endif
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500">
                        Keine Buchungen vorhanden
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    {{-- MOBILE --}}
    <div class="md:hidden space-y-4">

        @foreach($transactions as $t)

            @php
                $color = 'text-gray-800';
                $prefix = '';
                $isStorno = str_starts_with($t->description, 'Storno:');

                if (!$isStorno) {
                    if (in_array(optional($t->account_to)->type, ['bank', 'kasse'])) {
                        $color = 'text-green-600 font-semibold';
                        $prefix = '+ ';
                    } elseif (in_array(optional($t->account_from)->type, ['bank', 'kasse'])) {
                        $color = 'text-red-600 font-semibold';
                        $prefix = '- ';
                    }
                }
            @endphp

            <div class="bg-white rounded-xl shadow p-4 space-y-2">

                <div class="flex justify-between">

                    <div class="font-bold {{ $color }}">
                        {{ $t->description }}
                    </div>

                    <div class="font-mono {{ $color }}">
                        {{ $prefix }}{{ number_format($t->amount, 2, ',', '.') }} €
                    </div>

                </div>

                <div class="text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse($t->date)->format('d.m.Y') }}
                </div>

                <div class="text-sm">
                    {{ $t->account_from->name ?? '-' }}
                    →
                    {{ $t->account_to->name ?? '-' }}
                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection