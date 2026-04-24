@extends('layouts.app')

@section('title', 'Kontenübersicht')

@section('content')
<div 
    x-data="accountManager()" 
    x-init="init()" 
    class="space-y-8 max-w-5xl mx-auto"
>

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-800">📘 Konten</h1>

            {{-- Tooltip --}}
            <div x-data="{ open: false }" class="relative ml-2">
                <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                      class="cursor-pointer text-gray-400">ℹ️</span>

                <div x-show="open" x-transition
                     class="absolute z-50 w-64 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                    Übersicht aller Konten. Hier verwaltest du Bank, Kasse und Erlöskonten.
                </div>
            </div>
        </div>

        <button @click="create()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow">
            ➕ Neues Konto
        </button>
    </div>

    {{-- Tabs --}}
    <div class="flex border-b border-gray-200 space-x-6">

        {{-- Bank & Barkonten --}}
        <div class="flex items-center">
            <button @click="tab = 'balance'"
                    :class="tab === 'balance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="pb-2 border-b-2 font-medium text-sm">
                💶 Bank & Barkonten
            </button>

            <div x-data="{ open: false }" class="relative ml-1">
                <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                      class="cursor-pointer text-gray-400 text-xs">ℹ️</span>

                <div x-show="open" x-transition
                     class="absolute z-50 w-64 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                    Konten mit echtem Geldbestand (Bank, Kasse).
                </div>
            </div>
        </div>

        {{-- Erlöskonten --}}
        <div class="flex items-center">
            <button @click="tab = 'erloes'"
                    :class="tab === 'erloes' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="pb-2 border-b-2 font-medium text-sm">
                📂 Erlöskonten
            </button>

            <div x-data="{ open: false }" class="relative ml-1">
                <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                      class="cursor-pointer text-gray-400 text-xs">ℹ️</span>

                <div x-show="open" x-transition
                     class="absolute z-50 w-64 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                    Einnahme- und Ausgabenkonten (Buchhaltung).
                </div>
            </div>
        </div>

        {{-- Inaktive --}}
        <div class="flex items-center">
            <button @click="tab = 'inaktiv'"
                    :class="tab === 'inaktiv' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="pb-2 border-b-2 font-medium text-sm">
                🚫 Inaktive Konten
            </button>

            <div x-data="{ open: false }" class="relative ml-1">
                <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                      class="cursor-pointer text-gray-400 text-xs">ℹ️</span>

                <div x-show="open" x-transition
                     class="absolute z-50 w-64 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                    Deaktivierte Konten. Werden nicht mehr aktiv genutzt.
                </div>
            </div>
        </div>

    </div>

    {{-- Bank & Kasse --}}
    <div x-show="tab === 'balance'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($balanceAccounts as $account)
                <div class="flex justify-between items-center bg-white p-4 rounded shadow">
                    <div>
                        <p class="font-medium">{{ $account->name }}</p>
                        <p class="text-sm text-gray-500">{{ $account->type }} · {{ $account->number }}</p>
                        <p class="text-sm text-gray-600">
                            💶 Kontostand: {{ number_format($account->balance_current ?? $account->balance_start, 2, ',', '.') }} €
                        </p>
                    </div>
                    <button @click="edit({{ $account->toJson() }})"
                            class="text-blue-600 hover:underline text-sm">
                        Bearbeiten
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Erlöskonten --}}
    <div x-show="tab === 'erloes'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($chartAccounts as $account)
                <div class="flex justify-between items-center bg-white p-4 rounded shadow">
                    <div>
                        <p class="font-medium">{{ $account->name }}</p>
                        <p class="text-sm text-gray-500">{{ $account->type }} · {{ $account->number }}</p>
                        <p class="text-sm text-gray-600">
                            💶 Kontostand: {{ number_format($account->balance_current ?? $account->balance_start, 2, ',', '.') }} €
                        </p>
                    </div>
                    <button @click="edit({{ $account->toJson() }})"
                            class="text-blue-600 hover:underline text-sm">
                        Bearbeiten
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Inaktive Konten --}}
    <div x-show="tab === 'inaktiv'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($inactiveAccounts as $account)
                <div class="flex justify-between items-center bg-gray-100 p-4 rounded shadow-sm">
                    <div>
                        <p class="font-medium">{{ $account->name }}</p>
                        <p class="text-sm text-gray-500">{{ $account->type }} · {{ $account->number }}</p>
                        <p class="text-sm text-gray-600">
                            💶 Kontostand: {{ number_format($account->balance_current ?? $account->balance_start, 2, ',', '.') }} €
                        </p>
                    </div>
                    <button @click="edit({{ $account->toJson() }})"
                            class="text-blue-600 hover:underline text-sm">
                        Bearbeiten
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- MODAL --}}
    <div x-show="open"
         x-transition
         class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40"
         x-cloak>

        <div class="bg-white rounded-xl p-6 shadow-lg max-w-xl w-full relative">

            <button @click="close()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-800 text-xl">&times;</button>

            <h2 class="text-xl font-bold mb-4" x-text="account.id ? 'Konto bearbeiten' : 'Neues Konto anlegen'"></h2>

            <form @submit.prevent="submitForm">
                <div class="space-y-4">

                    {{-- Name --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 flex items-center">
                            Kontoname

                            <div x-data="{ open: false }" class="relative ml-1">
                                <span @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
                                      class="cursor-pointer text-gray-400 text-xs">ℹ️</span>

                                <div x-show="open" class="absolute z-50 w-56 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                    Frei wählbarer Name für das Konto.
                                </div>
                            </div>
                        </label>

                        <input type="text" x-model="account.name" class="mt-1 w-full rounded border-gray-300 text-sm shadow-sm" required>
                    </div>

                    {{-- Typ --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 flex items-center">
                            Typ

                            <div x-data="{ open: false }" class="relative ml-1">
                                <span class="cursor-pointer text-gray-400 text-xs">ℹ️</span>

                                <div x-show="open" class="absolute z-50 w-64 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                    Bestimmt die Kontoart (Bank, Kasse oder Buchhaltung).
                                </div>
                            </div>
                        </label>

                        <select x-model="account.type" class="mt-1 w-full rounded border-gray-300 text-sm shadow-sm" required>
                            <option value="bank">Bankkonto</option>
                            <option value="kasse">Kasse</option>
                            <option value="einnahme">Einnahme</option>
                            <option value="ausgabe">Ausgabe</option>
                        </select>
                    </div>

                    {{-- Steuerbereich --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 flex items-center">
                            Steuerlicher Bereich

                            <div x-data="{ open: false }" class="relative ml-1">
                                <span class="cursor-pointer text-gray-400 text-xs">ℹ️</span>

                                <div x-show="open" class="absolute z-50 w-72 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                    Ideell, Zweckbetrieb oder wirtschaftlich – wichtig für Steuer.
                                </div>
                            </div>
                        </label>

                        <select x-model="account.tax_area" class="mt-1 w-full rounded border-gray-300 text-sm shadow-sm" required>
                            <option value="">Bitte wählen</option>
                            <option value="ideell">Ideeller Bereich</option>
                            <option value="zweckbetrieb">Zweckbetrieb</option>
                            <option value="wirtschaftlich">Wirtschaftlicher Geschäftsbetrieb</option>
                        </select>
                    </div>

                    {{-- IBAN --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 flex items-center">
                            IBAN

                            <div x-data="{ open: false }" class="relative ml-1">
                                <span class="cursor-pointer text-gray-400 text-xs">ℹ️</span>

                                <div x-show="open" class="absolute z-50 w-56 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                    Nur für Bankkonten relevant.
                                </div>
                            </div>
                        </label>

                        <input type="text" x-model="account.iban" class="mt-1 w-full rounded border-gray-300 text-sm shadow-sm">
                    </div>

                    {{-- Anfangsbestand --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 flex items-center">
                            Anfangsbestand (€)

                            <div x-data="{ open: false }" class="relative ml-1">
                                <span class="cursor-pointer text-gray-400 text-xs">ℹ️</span>

                                <div x-show="open" class="absolute z-50 w-64 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                    Startwert des Kontos zum gewählten Datum.
                                </div>
                            </div>
                        </label>

                        <input type="number" step="0.01" x-model="account.balance_start" class="mt-1 w-full rounded border-gray-300 text-sm shadow-sm">
                    </div>

                    {{-- Aktiv --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 flex items-center">
                            Aktiv

                            <div x-data="{ open: false }" class="relative ml-1">
                                <span class="cursor-pointer text-gray-400 text-xs">ℹ️</span>

                                <div x-show="open" class="absolute z-50 w-64 p-2 text-xs text-white bg-gray-800 rounded-lg shadow top-6">
                                    Deaktivierte Konten erscheinen nur im Archiv.
                                </div>
                            </div>
                        </label>

                        <input type="checkbox" x-model="account.active" class="ml-2">
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 rounded shadow-sm">
                            💾 Speichern
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT bleibt unverändert --}}
<script>
    function accountManager() {
        return {
            tab: 'balance',
            open: false,
            account: {},
            init() {
                this.tab = 'balance';
            },
            create() {
                this.account = {
                    name: '',
                    type: 'bank',
                    tax_area: '',
                    iban: '',
                    bic: '',
                    description: '',
                    balance_start: 0,
                    balance_date: '',
                    active: true,
                    online: false
                };
                this.open = true;
            },
            edit(data) {
                this.account = {
                    ...data,
                    active: Boolean(Number(data.active)),
                    online: Boolean(Number(data.online))
                };
                this.open = true;
            },
            close() {
                this.open = false;
            },
            submitForm() {
                const isNew = !this.account.id;
                const url = isNew ? '/accounts' : `/accounts/${this.account.id}`;
                const method = 'POST';

                const payload = {
                    ...this.account,
                    active: this.account.active ? 1 : 0,
                    online: this.account.online ? 1 : 0
                };

                if (!isNew) {
                    payload._method = 'PUT';
                }

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => {
                    if (response.ok) {
                        this.close();
                        window.location.reload();
                    } else {
                        alert('Fehler beim Speichern');
                    }
                });
            }
        }
    }
</script>
@endsection