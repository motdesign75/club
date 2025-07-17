<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">Neue Beitragsrechnung</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded shadow">
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

            {{-- Mitglied --}}
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
            </div>

            {{-- Datum --}}
            <div class="mb-4">
                <label for="invoice_date" class="block text-sm font-medium mb-1">Rechnungsdatum</label>
                <input type="date" name="invoice_date" id="invoice_date"
                       class="w-full border-gray-300 rounded"
                       value="{{ old('invoice_date', now()->format('Y-m-d')) }}" required>
            </div>

            {{-- Rabatt + USt --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="discount" class="block text-sm font-medium mb-1">Rabatt (%)</label>
                    <input type="number" step="0.01" name="discount" id="discount"
                           class="w-full border-gray-300 rounded"
                           value="{{ old('discount', 0) }}">
                </div>
                <div>
                    <label for="tax_rate" class="block text-sm font-medium mb-1">USt. (%)</label>
                    <input type="number" step="0.01" name="tax_rate" id="tax_rate"
                           class="w-full border-gray-300 rounded"
                           value="{{ old('tax_rate', 0) }}">
                </div>
            </div>

            {{-- Positionen --}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Rechnungspositionen</label>

                <div id="items-wrapper" class="space-y-4">
                    <div class="grid grid-cols-12 gap-2 items-center border p-4 rounded bg-gray-50 item-row">
                        <div class="col-span-5">
                            <input type="text" name="items[0][description]" class="w-full rounded border-gray-300"
                                   placeholder="Beschreibung" required>
                        </div>
                        <div class="col-span-2">
                            <input type="number" step="0.01" name="items[0][quantity]"
                                   class="w-full rounded border-gray-300" placeholder="Menge" required>
                        </div>
                        <div class="col-span-2">
                            <input type="text" name="items[0][unit]"
                                   class="w-full rounded border-gray-300" placeholder="Einheit">
                        </div>
                        <div class="col-span-2">
                            <input type="number" step="0.01" name="items[0][unit_price]"
                                   class="w-full rounded border-gray-300" placeholder="Einzelpreis (€)" required>
                        </div>
                        <div class="col-span-1 text-right">
                            <button type="button"
                                    class="remove-row text-red-600 font-bold text-xl leading-none hover:text-red-800"
                                    title="Zeile entfernen">×</button>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" id="add-item"
                            class="text-sm px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                        + Position hinzufügen
                    </button>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('invoices.index') }}"
                   class="px-4 py-2 rounded border text-gray-600 hover:bg-gray-100">
                    Abbrechen
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Speichern & PDF anzeigen
                </button>
            </div>
        </form>
    </div>

    {{-- JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let itemIndex = 1;

            document.getElementById('add-item').addEventListener('click', function () {
                const wrapper = document.getElementById('items-wrapper');
                const row = document.createElement('div');
                row.className = 'grid grid-cols-12 gap-2 items-center border p-4 rounded bg-gray-50 item-row';
                row.innerHTML = `
                    <div class="col-span-5">
                        <input type="text" name="items[${itemIndex}][description]" class="w-full rounded border-gray-300"
                               placeholder="Beschreibung" required>
                    </div>
                    <div class="col-span-2">
                        <input type="number" step="0.01" name="items[${itemIndex}][quantity]"
                               class="w-full rounded border-gray-300" placeholder="Menge" required>
                    </div>
                    <div class="col-span-2">
                        <input type="text" name="items[${itemIndex}][unit]"
                               class="w-full rounded border-gray-300" placeholder="Einheit">
                    </div>
                    <div class="col-span-2">
                        <input type="number" step="0.01" name="items[${itemIndex}][unit_price]"
                               class="w-full rounded border-gray-300" placeholder="Einzelpreis (€)" required>
                    </div>
                    <div class="col-span-1 text-right">
                        <button type="button"
                                class="remove-row text-red-600 font-bold text-xl leading-none hover:text-red-800"
                                title="Zeile entfernen">×</button>
                    </div>
                `;
                wrapper.appendChild(row);
                itemIndex++;
            });

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('.item-row').remove();
                }
            });
        });
    </script>
</x-app-layout>
