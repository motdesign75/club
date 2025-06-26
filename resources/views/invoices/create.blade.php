<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">Neue Beitragsrechnung</h2>
    </x-slot>

    <div class="max-w-xl mx-auto mt-6 bg-white p-6 rounded shadow">
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-300 text-red-800 p-3 rounded">
                <strong>Bitte überprüfe deine Eingaben:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('invoices.store') }}">
            @csrf

            {{-- Mitgliedsauswahl --}}
            <div class="mb-4">
                <label for="member_id" class="block text-sm font-medium mb-1">Mitglied</label>
                <select name="member_id" id="member_id" required class="w-full border-gray-300 rounded">
                    <option value="">Bitte auswählen</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->last_name }}, {{ $member->first_name }}
                        </option>
                    @endforeach
                </select>
                @error('member_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Rechnungsdatum --}}
            <div class="mb-4">
                <label for="invoice_date" class="block text-sm font-medium mb-1">Rechnungsdatum</label>
                <input type="date" name="invoice_date" id="invoice_date"
                       class="w-full border-gray-300 rounded"
                       value="{{ old('invoice_date', now()->format('Y-m-d')) }}" required>
                @error('invoice_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Betrag --}}
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium mb-1">Betrag (EUR)</label>
                <input type="number" step="0.01" name="amount" id="amount"
                       class="w-full border-gray-300 rounded"
                       value="{{ old('amount') }}" required>
                @error('amount') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Beschreibung --}}
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-1">Beschreibung (optional)</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full border-gray-300 rounded">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('invoices.index') }}"
                   class="px-4 py-2 rounded border text-gray-600 hover:bg-gray-100">
                    Abbrechen
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Speichern
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
