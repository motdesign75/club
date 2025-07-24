@extends('layouts.app')

@section('title', 'Neues Protokoll')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow rounded">
    <h2 class="text-xl font-semibold leading-tight mb-6">Neues Protokoll erstellen</h2>

    <form method="POST" action="{{ route('protocols.store') }}">
        @csrf

        {{-- Titel --}}
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Titel</label>
            <input id="title" name="title" type="text"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                   value="{{ old('title') }}" required>
            @error('title')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Typ --}}
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Protokolltyp</label>
            <select id="type" name="type"
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                    required>
                <option value="">-- Bitte wählen --</option>
                <option value="Mitgliederversammlung" {{ old('type') == 'Mitgliederversammlung' ? 'selected' : '' }}>Mitgliederversammlung</option>
                <option value="Vorstandssitzung" {{ old('type') == 'Vorstandssitzung' ? 'selected' : '' }}>Vorstandssitzung</option>
                <option value="Jahreshauptversammlung" {{ old('type') == 'Jahreshauptversammlung' ? 'selected' : '' }}>Jahreshauptversammlung</option>
                <option value="Sonstiges" {{ old('type') == 'Sonstiges' ? 'selected' : '' }}>Sonstiges</option>
            </select>
            @error('type')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ort --}}
        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-gray-700">Ort</label>
            <input id="location" name="location" type="text"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                   value="{{ old('location') }}" placeholder="z. B. Vereinsheim Gilde Eck">
            @error('location')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Beginn --}}
        <div class="mb-4">
            <label for="start_time" class="block text-sm font-medium text-gray-700">Beginn</label>
            <input id="start_time" name="start_time" type="time"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                   value="{{ old('start_time') }}">
            @error('start_time')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ende --}}
        <div class="mb-4">
            <label for="end_time" class="block text-sm font-medium text-gray-700">Ende</label>
            <input id="end_time" name="end_time" type="time"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                   value="{{ old('end_time') }}">
            @error('end_time')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Teilnehmer (Checkbox-Grid) --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Teilnehmer</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                @foreach ($members as $member)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="participant_ids[]" value="{{ $member->id }}"
                               {{ in_array($member->id, old('participant_ids', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring focus:ring-blue-200">
                        <span>{{ $member->full_name }}</span>
                    </label>
                @endforeach
            </div>
            @error('participant_ids')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Inhalt mit Trix --}}
        <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-gray-700">Inhalt</label>
            <input id="content" type="hidden" name="content" value="{{ old('content') }}">
            <trix-editor input="content" class="bg-white border rounded shadow-sm min-h-[300px] w-full p-2"></trix-editor>
            @error('content')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Speichern --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Speichern
            </button>
        </div>
    </form>
</div>
@endsection
