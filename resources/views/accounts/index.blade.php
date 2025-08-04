@extends('layouts.app')

@section('title', 'Kontenübersicht')

@section('content')
<div 
    x-data="accountManager()" 
    x-init="init()" 
    class="space-y-8 max-w-5xl mx-auto"
>
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">📘 Konten</h1>
        <a href="{{ route('accounts.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow">
            ➕ Neues Konto
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex border-b border-gray-200 space-x-6">
        <button @click="tab = 'balance'"
                :class="tab === 'balance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="pb-2 border-b-2 font-medium text-sm">
            💶 Bank & Barkonten
        </button>
        <button @click="tab = 'erloes'"
                :class="tab === 'erloes' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="pb-2 border-b-2 font-medium text-sm">
            📂 Erlöskonten
        </button>
        <button @click="tab = 'inaktiv'"
                :class="tab === 'inaktiv' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="pb-2 border-b-2 font-medium text-sm">
            🚫 Inaktive Konten
        </button>
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
                            💶 Kontostand: {{ number_format($account->balance_current ?? $account->balance_start, 2, ',', '.') }} €
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
                            💶 Kontostand: {{ number_format($account->balance_current ?? $account->balance_start, 2, ',', '.') }} €
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
                            💶 Kontostand: {{ number_format($account->balance_current ?? $account->balance_start, 2, ',', '.') }} €
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

    {{-- Modal --}}
    <div x-show="open"
         x-transition
         class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40"
         x-cloak>
        <div class="bg-white rounded-xl p-6 shadow-lg max-w-xl w-full relative">
            <button @click="close()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-800 text-xl">&times;</button>

            <h2 class="text-xl font-bold mb-4">Konto bearbeiten</h2>

            <form @submit.prevent="submitForm">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Kontoname</label>
                        <input type="text" x-model="account.name" class="mt-1 w-full rounded border-gray-300 text-sm shadow-sm" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Typ</label>
                        <select x-model="account.type" class="mt-1 w-full rounded border-gray-300 text-sm shadow-sm">
                            <option value="bank">Bankkonto</option>
                            <option value="kasse">Kasse</option>
                            <option value="einnahme">Einnahme</option>
                            <option value="ausgabe">Ausgabe</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Aktiv</label>
                        <input type="checkbox" x-model="account.active" class="ml-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Online</label>
                        <input type="checkbox" x-model="account.online" class="ml-2">
                    </div>
                    <div class="pt-4">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 rounded shadow-sm">
                            💾 Änderungen speichern
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function accountManager() {
        return {
            tab: 'balance',
            open: false,
            account: {},
            init() {
                this.tab = 'balance';
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
                fetch(`/accounts/${this.account.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        ...this.account,
                        active: this.account.active ? 1 : 0,
                        online: this.account.online ? 1 : 0
                    })
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
