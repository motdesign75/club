@extends('layouts.app')

@section('title', 'Buchung bearbeiten')

@section('content')

@if ($errors->any())
    <div class="max-w-xl mx-auto mb-6">
        <div class="bg-red-100 border border-red-300 text-red-700 p-4 rounded-xl shadow-sm">
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="w-full px-6">

    <!-- 🔹 HEADER (kompakt & modern) -->
    <div class="max-w-6xl mx-auto mb-6">
        <h1 class="text-xl font-semibold text-gray-800">
            ✏️ Buchung bearbeiten
        </h1>
        <p class="text-sm text-gray-500">
            Details anpassen und Beleg prüfen
        </p>
    </div>

    <!-- 🔥 LAYOUT -->
    <div class="flex flex-col xl:flex-row gap-8 max-w-6xl mx-auto">

        <!-- 🔹 FORM (JETZT SCHMAL & ANGENEHM) -->
        <div class="w-full xl:w-[520px]">

            <form method="POST"
                  action="{{ route('transactions.update', $transaction) }}"
                  enctype="multipart/form-data"
                  class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">

                @csrf
                @method('PUT')

                <!-- Basis -->
                <div class="space-y-4">

                    <div>
                        <label class="text-xs text-gray-500 uppercase">Datum</label>
                        <input type="date" name="date"
                               value="{{ old('date', $transaction->date->format('Y-m-d')) }}"
                               class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label class="text-xs text-gray-500 uppercase">Beschreibung</label>
                        <input type="text" name="description"
                               value="{{ old('description', $transaction->description) }}"
                               class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label class="text-xs text-gray-500 uppercase">Betrag (€)</label>
                        <input type="number" name="amount"
                               value="{{ old('amount', $transaction->amount) }}"
                               step="0.01"
                               class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500" />
                    </div>

                </div>

                <!-- Kontierung -->
                <div class="space-y-4 border-t pt-5">

                    <div>
                        <label class="text-xs text-gray-500 uppercase">Von-Konto</label>
                        <select name="account_from_id"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('account_from_id', $transaction->account_from_id) == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500 uppercase">Nach-Konto</label>
                        <select name="account_to_id"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('account_to_id', $transaction->account_to_id) == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500 uppercase">Steuerbereich</label>
                        <select name="tax_area"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                            <option value="ideell" {{ old('tax_area', $transaction->tax_area)=='ideell'?'selected':'' }}>Ideell</option>
                            <option value="zweckbetrieb" {{ old('tax_area', $transaction->tax_area)=='zweckbetrieb'?'selected':'' }}>Zweckbetrieb</option>
                            <option value="wirtschaftlich" {{ old('tax_area', $transaction->tax_area)=='wirtschaftlich'?'selected':'' }}>Wirtschaftlich</option>
                        </select>
                    </div>

                </div>

                <!-- Beleg -->
                <div class="border-t pt-5 space-y-2">

                    <label class="text-xs text-gray-500 uppercase">
                        Beleg
                    </label>

                    <input type="file"
                           name="receipt_file"
                           class="w-full text-sm" />

                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4">

                    <a href="{{ route('transactions.index') }}"
                       class="text-sm text-gray-500 hover:text-gray-700">
                        ← Zurück
                    </a>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow-sm">
                        💾 Speichern
                    </button>

                </div>

            </form>

        </div>

        <!-- 🔹 BELEG (BLEIBT GROSS = ARBEITSBEREICH) -->
        <div class="flex-1">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 h-[80vh] flex flex-col overflow-hidden">

                <div class="px-4 py-3 border-b bg-gray-50 flex justify-between">
                    <span class="text-sm font-medium text-gray-600">
                        📄 Beleg
                    </span>

                    @if($transaction->receipt_file)
                        <a href="{{ route('receipts.show', $transaction->receipt_file) }}"
                           target="_blank"
                           class="text-xs text-blue-600">
                            Öffnen
                        </a>
                    @endif
                </div>

                <div class="flex-1 bg-gray-100 flex items-center justify-center">

                    @if($transaction->receipt_file)

                        @php
                            $ext = strtolower(pathinfo($transaction->receipt_file, PATHINFO_EXTENSION));
                            $url = route('receipts.show', $transaction->receipt_file);
                        @endphp

                        @if($ext === 'pdf')
                            <iframe src="{{ $url }}#zoom=page-width"
                                    class="w-full h-full"></iframe>
                        @else
                            <img src="{{ $url }}"
                                 class="max-w-full max-h-full object-contain">
                        @endif

                    @else
                        <div class="text-gray-400 text-sm">
                            Kein Beleg vorhanden
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection