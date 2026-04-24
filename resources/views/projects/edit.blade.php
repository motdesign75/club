@extends('layouts.app')

@section('title', 'Projekt bearbeiten')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Projekt bearbeiten</h1>
            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Zur Übersicht
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                <ul class="list-disc ms-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projects.update', $project) }}" method="POST" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-600">*</span></label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $project->name) }}"
                       required maxlength="255"
                       class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Beschreibung</label>
                <textarea name="description" id="description" rows="6"
                          class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('description', $project->description) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('projects.show', $project) }}"
                   class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Abbrechen
                </a>
                <button type="submit"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Speichern
                </button>
            </div>
        </form>

        <div class="rounded-lg border border-red-200 bg-red-50 p-5">
            <h2 class="text-sm font-semibold text-red-800">Gefährlicher Bereich</h2>
            <p class="mt-1 text-sm text-red-700">Das Löschen kann nicht rückgängig gemacht werden.</p>
            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="mt-3"
                  onsubmit="return confirm('Dieses Projekt wirklich dauerhaft löschen?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Projekt löschen
                </button>
            </form>
        </div>
    </div>
@endsection
