@extends('layouts.app')

@section('title', 'Neues Protokoll')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto bg-gray-50 p-8 shadow-lg rounded-lg space-y-8">
    <h2 class="text-2xl font-bold text-center text-gray-800">📋 Neues Protokoll erstellen</h2>

    {{-- WICHTIG: enctype ergänzt --}}
    <form method="POST" action="{{ route('protocols.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Titel --}}
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700">Titel</label>
            <input id="title" name="title" type="text"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                   value="{{ old('title') }}" placeholder="z. B. Vorstandssitzung" required>
            @error('title')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Typ --}}
        <div class="mb-6">
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

        {{-- Ort und Zeitraum --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Ort</label>
                <input id="location" name="location" type="text"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                       value="{{ old('location') }}" placeholder="z. B. Vereinsheim">
                @error('location')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700">Beginn</label>
                <input id="start_time" name="start_time" type="time"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                       value="{{ old('start_time') }}">
                @error('start_time')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700">Ende</label>
                <input id="end_time" name="end_time" type="time"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                       value="{{ old('end_time') }}">
                @error('end_time')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Teilnehmer --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Teilnehmer</label>
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

        {{-- Beschlüsse --}}
        <div class="mb-6">
            <label for="resolutions" class="block text-sm font-medium text-gray-700">Beschlüsse / Ergebnisse</label>
            <textarea id="resolutions" name="resolutions"
                      class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                      rows="4">{{ old('resolutions') }}</textarea>
            @error('resolutions')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nächstes Treffen --}}
        <div class="mb-6">
            <label for="next_meeting" class="block text-sm font-medium text-gray-700">Details zum nächsten Treffen</label>
            <textarea id="next_meeting" name="next_meeting"
                      class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                      rows="3">{{ old('next_meeting') }}</textarea>
            @error('next_meeting')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- NEU: Anhänge --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Anhänge</label>
            <input type="file" name="attachments[]" multiple
                   class="mt-1 block w-full border rounded p-2 bg-white">

            <p class="text-xs text-gray-500 mt-1">
                Erlaubt: PDF, Bilder, Word, Excel (max. 10MB pro Datei)
            </p>

            @error('attachments')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Inhalt --}}
        <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-gray-700">Protokoll</label>
            <input id="content" type="hidden" name="content" value="{{ old('content') }}">
            <trix-editor input="content" class="bg-white border rounded shadow-sm min-h-[300px]"></trix-editor>
            @error('content')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Speichern --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                Speichern
            </button>
        </div>
    </form>
</div>
@endsection