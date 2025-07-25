@extends('layouts.app')

@section('title', 'Mitglieder-Import')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded shadow p-6 space-y-6">
        <h1 class="text-2xl font-bold text-gray-800">📥 Mitgliederimport</h1>

        {{-- Session-Fehlermeldung --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validierungsfehler --}}
        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded">
                <strong>Fehler:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Upload-Formular --}}
        <form action="{{ route('import.mitglieder.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="csv_file" class="block font-medium text-sm text-gray-700">CSV-Datei auswählen:</label>
                <input
                    type="file"
                    name="csv_file"
                    id="csv_file"
                    accept=".csv"
                    required
                    class="mt-1 block w-full border rounded p-2 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                >
                <p class="text-sm text-gray-500 mt-1">Format: CSV mit Kopfzeile (z. B. Vorname, Nachname, E-Mail ...)</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white font-semibold px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📄 Vorschau anzeigen
                </button>
            </div>
        </form>
    </div>
@endsection
