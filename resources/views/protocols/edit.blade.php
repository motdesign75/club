@extends('layouts.app')

@section('title', 'Protokoll bearbeiten')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.umd.min.js"></script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow rounded space-y-6">

    <h2 class="text-xl font-semibold">Protokoll bearbeiten</h2>

    {{-- WICHTIG: enctype für Upload --}}
    <form method="POST" action="{{ route('protocols.update', $protocol) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Titel --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Titel</label>
            <input name="title" type="text"
                   class="mt-1 w-full rounded border-gray-300"
                   value="{{ old('title', $protocol->title) }}" required>
        </div>

        {{-- Typ --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Protokolltyp</label>
            <select name="type" class="mt-1 w-full rounded border-gray-300" required>
                <option value="">-- Bitte wählen --</option>
                <option value="Mitgliederversammlung" {{ old('type', $protocol->type) == 'Mitgliederversammlung' ? 'selected' : '' }}>Mitgliederversammlung</option>
                <option value="Vorstandssitzung" {{ old('type', $protocol->type) == 'Vorstandssitzung' ? 'selected' : '' }}>Vorstandssitzung</option>
                <option value="Jahreshauptversammlung" {{ old('type', $protocol->type) == 'Jahreshauptversammlung' ? 'selected' : '' }}>Jahreshauptversammlung</option>
                <option value="Sonstiges" {{ old('type', $protocol->type) == 'Sonstiges' ? 'selected' : '' }}>Sonstiges</option>
            </select>
        </div>

        {{-- Ort / Zeit --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <input name="location" placeholder="Ort"
                   class="border rounded p-2"
                   value="{{ old('location', $protocol->location) }}">

            <input name="start_time" type="time"
                   class="border rounded p-2"
                   value="{{ old('start_time', $protocol->start_time) }}">

            <input name="end_time" type="time"
                   class="border rounded p-2"
                   value="{{ old('end_time', $protocol->end_time) }}">
        </div>

        {{-- Teilnehmer --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Teilnehmer</label>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach ($members as $member)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox"
                               name="participant_ids[]"
                               value="{{ $member->id }}"
                               {{ in_array($member->id, old('participant_ids', $selected)) ? 'checked' : '' }}>
                        <span>{{ $member->full_name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Beschlüsse --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Beschlüsse / Ergebnisse</label>
            <textarea name="resolutions"
                      class="mt-1 w-full border rounded p-2"
                      rows="4">{{ old('resolutions', $protocol->resolutions) }}</textarea>
        </div>

        {{-- Nächstes Treffen --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Nächstes Treffen</label>
            <textarea name="next_meeting"
                      class="mt-1 w-full border rounded p-2"
                      rows="3">{{ old('next_meeting', $protocol->next_meeting) }}</textarea>
        </div>

        {{-- Anhänge anzeigen --}}
        @php
            $attachments = $protocol->attachments ?? $protocol->attachment_paths ?? [];
            if (is_string($attachments)) $attachments = [$attachments];
        @endphp

        @if(!empty($attachments))
            <div>
                <label class="block text-sm font-medium text-gray-700">Bestehende Anhänge</label>

                <ul class="mt-2 space-y-1">
                    @foreach($attachments as $file)
                        <li>
                            <a href="{{ asset('storage/'.$file) }}"
                               target="_blank"
                               class="text-blue-600 hover:underline text-sm">
                                📄 {{ basename($file) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Neue Anhänge --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Neue Anhänge hinzufügen</label>
            <input type="file" name="attachments[]" multiple
                   class="mt-1 w-full border rounded p-2">
        </div>

        {{-- Inhalt --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Protokoll</label>
            <input id="content" type="hidden" name="content" value="{{ old('content', $protocol->content) }}">
            <trix-editor input="content" class="bg-white border rounded min-h-[300px]"></trix-editor>
        </div>

        {{-- Aktionen --}}
        <div class="flex justify-between items-center pt-4">

            <a href="{{ route('protocols.index') }}"
               class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                ← Zurück
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Speichern
            </button>

        </div>

    </form>
</div>
@endsection