@extends('layouts.app')

@section('title', 'Veranstaltungen')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Kopfbereich + Button --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">📅 Veranstaltungen</h1>
        <a href="{{ route('events.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow transition">
            ➕ Neue Veranstaltung
        </a>
    </div>

    {{-- Erfolgsmeldung --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Eventliste --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($events as $event)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-6 flex flex-col justify-between">
                <div class="space-y-2">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h2>

                    <p class="text-sm text-gray-600">
                        📍 {{ $event->location ?? 'Ort folgt' }}
                    </p>

                    <p class="text-sm text-gray-600">
                        📆 {{ $event->start->format('d.m.Y H:i') }} – {{ $event->end->format('H:i') }}
                    </p>

                    @if($event->is_public)
                        <span class="inline-block px-3 py-0.5 text-xs font-medium text-green-700 bg-green-100 rounded-full">Öffentlich</span>
                    @else
                        <span class="inline-block px-3 py-0.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">Intern</span>
                    @endif
                </div>

                {{-- Aktionen --}}
                <div class="flex justify-end items-center gap-4 mt-6">
                    <a href="{{ route('events.edit', $event) }}"
                       class="text-sm text-blue-600 hover:underline">
                        ✏️ Bearbeiten
                    </a>

                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Wirklich löschen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">
                            🗑️ Löschen
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-461 bg-gray-50 p-10 rounded-xl shadow-sm">
                🎉 Noch keine Veranstaltungen angelegt.
            </div>
        @endforelse
    </div>
</div>
@endsection
