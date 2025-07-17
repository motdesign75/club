@extends('layouts.app')

@section('title', 'Neue Veranstaltung')

@section('content')
<div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Kopfbereich --}}
    <div class="mb-10">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-2">
            ➕ Neue Veranstaltung erstellen
        </h1>
        <p class="mt-2 text-sm text-gray-600">Plane ein neues Event mit Titel, Beschreibung, Ort und Zeitangaben.</p>
    </div>

    {{-- Formular --}}
    <form action="{{ route('events.store') }}" method="POST"
          class="space-y-6 bg-white border border-gray-200 shadow-md rounded-2xl p-6 sm:p-8">
        @csrf

        {{-- Titel --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">📌 Titel</label>
            <input type="text" name="title" id="title" required
                   value="{{ old('title') }}"
                   class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
        </div>

        {{-- Beschreibung --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">📝 Beschreibung</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm">{{ old('description') }}</textarea>
        </div>

        {{-- Ort --}}
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">📍 Ort</label>
            <input type="text" name="location" id="location"
                   value="{{ old('location') }}"
                   class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
        </div>

        {{-- Zeit --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="start" class="block text-sm font-medium text-gray-700 mb-1">⏱ Beginn</label>
                <input type="datetime-local" name="start" id="start" required
                       value="{{ old('start') }}"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
            </div>

            <div>
                <label for="end" class="block text-sm font-medium text-gray-700 mb-1">⏱ Ende</label>
                <input type="datetime-local" name="end" id="end" required
                       value="{{ old('end') }}"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
            </div>
        </div>

        {{-- Sichtbarkeit --}}
        <div>
            <label for="is_public" class="block text-sm font-medium text-gray-700 mb-1">🔒 Sichtbarkeit</label>
            <select name="is_public" id="is_public"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                <option value="1" {{ old('is_public') == '1' ? 'selected' : '' }}>Öffentlich</option>
                <option value="0" {{ old('is_public') == '0' ? 'selected' : '' }}>Intern</option>
            </select>
        </div>

        {{-- Aktionen --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-100">
            <a href="{{ route('events.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">
                ← Zurück zur Übersicht
            </a>

            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow transition">
                💾 Veranstaltung speichern
            </button>
        </div>
    </form>

    {{-- Teilen (sichtbare Schaltflächen) --}}
    @isset($event)
    <div class="mt-10 bg-white border border-gray-200 rounded-2xl p-6 shadow-md space-y-4">

        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            📤 Veranstaltung teilen
        </h2>

        <div class="flex flex-col sm:flex-row gap-4">
            {{-- Button: Link anzeigen --}}
            <button type="button"
                    onclick="document.getElementById('share-link-box').classList.toggle('hidden')"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow transition w-full sm:w-auto">
                🔗 Teilen als Link
            </button>

            {{-- Button: Einbettungscode anzeigen --}}
            <button type="button"
                    onclick="document.getElementById('embed-code-box').classList.toggle('hidden')"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow transition w-full sm:w-auto">
                🌐 Zur Einbindung auf Webseite
            </button>
        </div>

        {{-- Direktlink anzeigen --}}
        <div id="share-link-box" class="hidden mt-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">🔗 Direktlink zur Veranstaltung:</label>
            <div class="flex gap-2">
                <input type="text"
                       value="{{ route('events.show', $event->id) }}"
                       readonly
                       class="w-full rounded-xl border-gray-300 shadow-sm text-sm text-gray-700 focus:outline-none">
                <button type="button"
                        onclick="navigator.clipboard.writeText(this.previousElementSibling.value)"
                        class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                    Kopieren
                </button>
            </div>
        </div>

        {{-- Einbettungscode anzeigen --}}
        <div id="embed-code-box" class="hidden mt-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">📎 HTML-Code zur Einbindung:</label>
            <div class="relative">
                <textarea rows="3" readonly
                          class="w-full rounded-xl border-gray-300 shadow-sm text-sm text-gray-700 pr-24 focus:outline-none resize-none">
<a href="{{ route('events.show', $event->id) }}" target="_blank">Jetzt Veranstaltung ansehen</a>
                </textarea>
                <button type="button"
                        onclick="navigator.clipboard.writeText(this.previousElementSibling.value)"
                        class="absolute top-2 right-2 px-3 py-1.5 text-sm bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                    Kopieren
                </button>
            </div>
        </div>

    </div>
    @endisset
</div>
@endsection
