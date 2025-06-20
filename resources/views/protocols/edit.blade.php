@extends('layouts.sidebar')

@section('title', 'Protokoll bearbeiten')

@push('head')
    {{-- Trix Editor Styles – jsDelivr (funktionierende Quelle) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.css">
@endpush

@push('scripts')
    {{-- Trix Editor Script – jsDelivr --}}
    <script src="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.umd.min.js"></script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow rounded">
    <h2 class="text-xl font-semibold leading-tight mb-6">Protokoll bearbeiten</h2>

    <form method="POST" action="{{ route('protocols.update', $protocol) }}">
        @csrf
        @method('PUT')

        {{-- Titel --}}
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Titel</label>
            <input id="title" name="title" type="text"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                   value="{{ old('title', $protocol->title) }}" required>
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
                <option value="Mitgliederversammlung" {{ old('type', $protocol->type) == 'Mitgliederversammlung' ? 'selected' : '' }}>Mitgliederversammlung</option>
                <option value="Vorstandssitzung" {{ old('type', $protocol->type) == 'Vorstandssitzung' ? 'selected' : '' }}>Vorstandssitzung</option>
                <option value="Jahreshauptversammlung" {{ old('type', $protocol->type) == 'Jahreshauptversammlung' ? 'selected' : '' }}>Jahreshauptversammlung</option>
                <option value="Sonstiges" {{ old('type', $protocol->type) == 'Sonstiges' ? 'selected' : '' }}>Sonstiges</option>
            </select>
            @error('type')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Teilnehmer --}}
        <div class="mb-6">
            <label for="participant_ids" class="block text-sm font-medium text-gray-700">Teilnehmer</label>
            <select id="participant_ids" name="participant_ids[]" multiple
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                @foreach ($members as $member)
                    <option value="{{ $member->id }}"
                        {{ in_array($member->id, old('participant_ids', $protocol->participants->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $member->full_name }}
                    </option>
                @endforeach
            </select>
            @error('participant_ids')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Inhalt mit Trix --}}
        <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-gray-700">Inhalt</label>
            <input id="content" type="hidden" name="content" value="{{ old('content', $protocol->content) }}">
            <trix-editor input="content" class="bg-white border rounded shadow-sm w-full p-2" style="min-height: 300px;"></trix-editor>
            @error('content')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Speichern --}}
        <div class="flex justify-end space-x-2">
            <a href="{{ route('protocols.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded hover:bg-gray-300">
                Zurück
            </a>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Speichern
            </button>
        </div>
    </form>
</div>
@endsection
