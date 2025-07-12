@extends('layouts.sidebar')

@section('title', 'Neue Mitgliedschaft')

@section('content')
    <div class="max-w-xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold text-gray-800">➕ Neue Mitgliedschaft</h1>

        <form method="POST" action="{{ route('memberships.store') }}">
            @csrf

            <div class="space-y-4 bg-white p-6 rounded shadow">
                {{-- Bezeichnung --}}
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Bezeichnung</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full mt-1 border rounded px-3 py-2">
                </div>

                {{-- Beitrag --}}
                <div>
                    <label for="amount" class="block font-medium text-sm text-gray-700">Beitrag (€)</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount') }}" required class="w-full mt-1 border rounded px-3 py-2">
                </div>

                {{-- Abrechnungsintervall --}}
                <div>
                    <label for="interval" class="block font-medium text-sm text-gray-700">Abrechnungsintervall</label>
                    <select name="interval" id="interval" required class="w-full mt-1 border rounded px-3 py-2">
                        <option value="monatlich" {{ old('interval') === 'monatlich' ? 'selected' : '' }}>monatlich</option>
                        <option value="vierteljährlich" {{ old('interval') === 'vierteljährlich' ? 'selected' : '' }}>vierteljährlich</option>
                        <option value="halbjährlich" {{ old('interval') === 'halbjährlich' ? 'selected' : '' }}>halbjährlich</option>
                        <option value="jährlich" {{ old('interval', 'jährlich') === 'jährlich' ? 'selected' : '' }}>jährlich</option>
                    </select>
                </div>

                {{-- Speichern --}}
                <div class="text-right pt-4">
                    <x-primary-button>💾 Speichern</x-primary-button>
                </div>
            </div>
        </form>
    </div>
@endsection
