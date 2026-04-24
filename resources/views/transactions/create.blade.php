@extends('layouts.app')

@section('title', 'Neue Buchung')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-[#2954A3]">
            Neue Buchung
        </h1>

        <p class="text-sm text-gray-500">
            Einnahme, Ausgabe oder Umbuchung erfassen
        </p>
    </div>

    <form method="POST"
          action="{{ route('transactions.store') }}"
          enctype="multipart/form-data"
          class="space-y-6">

        @csrf

        {{-- BOX --}}
        <div class="bg-white shadow rounded-xl p-6 space-y-6">

            {{-- GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Datum --}}
                <div>
                    <label class="text-sm text-gray-600 flex items-center">
                        Datum *
                        <div x-data="{ open: false }" class="relative ml-1">
                            <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                                  class="cursor-pointer text-gray-400">ℹ️</span>
                            <div x-show="open" x-transition
                                 class="absolute z-50 w-56 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                Buchungsdatum. Standard ist heute.
                            </div>
                        </div>
                    </label>

                    <input
                        type="date"
                        name="date"
                        value="{{ old('date', now()->format('Y-m-d')) }}"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                    @error('date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Betrag --}}
                <div>
                    <label class="text-sm text-gray-600 flex items-center">
                        Betrag (€) *
                        <div x-data="{ open: false }" class="relative ml-1">
                            <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                                  class="cursor-pointer text-gray-400">ℹ️</span>
                            <div x-show="open" x-transition
                                 class="absolute z-50 w-56 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                Betrag immer positiv eingeben. Richtung ergibt sich aus den Konten.
                            </div>
                        </div>
                    </label>

                    <input
                        type="number"
                        name="amount"
                        value="{{ old('amount') }}"
                        step="0.01"
                        class="w-full border rounded-lg p-2"
                        placeholder="0.00"
                        required
                    >

                    @error('amount')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Steuerbereich --}}
                <div>
                    <label class="text-sm text-gray-600 flex items-center">
                        Steuerbereich *
                        <div x-data="{ open: false }" class="relative ml-1">
                            <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                                  class="cursor-pointer text-gray-400">ℹ️</span>
                            <div x-show="open" x-transition
                                 class="absolute z-50 w-72 p-3 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                <strong>Ideell:</strong> Beiträge, Spenden<br>
                                <strong>Zweckbetrieb:</strong> Veranstaltungen<br>
                                <strong>Wirtschaftlich:</strong> Verkauf / Gewinnabsicht
                            </div>
                        </div>
                    </label>

                    <select name="tax_area" class="w-full border rounded-lg p-2" required>
                        <option value="">Bitte wählen</option>
                        <option value="ideell" {{ old('tax_area')=='ideell'?'selected':'' }}>Ideeller Bereich</option>
                        <option value="zweckbetrieb" {{ old('tax_area')=='zweckbetrieb'?'selected':'' }}>Zweckbetrieb</option>
                        <option value="wirtschaftlich" {{ old('tax_area')=='wirtschaftlich'?'selected':'' }}>Wirtschaftlicher Betrieb</option>
                    </select>
                </div>

                {{-- Beschreibung --}}
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600 flex items-center">
                        Beschreibung *
                        <div x-data="{ open: false }" class="relative ml-1">
                            <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                                  class="cursor-pointer text-gray-400">ℹ️</span>
                            <div x-show="open" x-transition
                                 class="absolute z-50 w-64 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                Kurz und eindeutig beschreiben.
                            </div>
                        </div>
                    </label>

                    <textarea name="description" rows="3"
                        class="w-full border rounded-lg p-2"
                        required>{{ old('description') }}</textarea>
                </div>

                {{-- Konten --}}
                <div>
                    <label class="text-sm text-gray-600">Von Konto *</label>
                    <select name="account_from_id" class="w-full border rounded-lg p-2" required>
                        <option value="">Bitte wählen</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('account_from_id')==$account->id?'selected':'' }}>
                                {{ $account->number ? $account->number.' - ' : '' }}{{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Nach Konto *</label>
                    <select name="account_to_id" class="w-full border rounded-lg p-2" required>
                        <option value="">Bitte wählen</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('account_to_id')==$account->id?'selected':'' }}>
                                {{ $account->number ? $account->number.' - ' : '' }}{{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- FILE UPLOAD (VERBESSERT) --}}
            <div>
                <label class="text-sm text-gray-600 flex items-center">
                    Beleg hochladen
                    <div x-data="{ open: false }" class="relative ml-1">
                        <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                              class="cursor-pointer text-gray-400">ℹ️</span>
                        <div x-show="open" x-transition
                             class="absolute z-50 w-64 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                            Optional, aber empfohlen. Direkt per Kamera möglich.
                        </div>
                    </div>
                </label>

                <input
                    type="file"
                    name="receipt_file"
                    accept="image/*,.pdf"
                    capture="environment"
                    class="w-full border rounded-lg p-2"
                >

                @error('receipt_file')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                <p class="text-xs text-gray-400 mt-1">
                    PDF / Bild · Kamera wird auf mobilen Geräten direkt geöffnet
                </p>
            </div>

        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-between">
            <a href="{{ route('transactions.index') }}"
               class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                Zurück
            </a>

            <button type="submit"
                class="px-6 py-2 bg-[#2954A3] text-white rounded-lg shadow hover:bg-blue-700">
                Buchung speichern
            </button>
        </div>

    </form>

</div>

@endsection