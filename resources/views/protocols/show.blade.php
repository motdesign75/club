@extends('layouts.app')

@section('title', 'Protokoll anzeigen')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow rounded">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ $protocol->title }}</h2>

    {{-- Metadaten --}}
    <div class="mb-6 text-sm text-gray-600 space-y-1">
        <p><strong>Typ:</strong> {{ $protocol->type }}</p>
        <p><strong>Erstellt am:</strong> {{ $protocol->created_at->format('d.m.Y H:i') }}</p>

        @if($protocol->participants && $protocol->participants->count() > 0)
            <p><strong>Teilnehmer:</strong></p>
            <ul class="list-disc list-inside text-gray-700">
                @foreach ($protocol->participants as $member)
                    <li>{{ $member->full_name }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Inhalt (Trix-HTML) --}}
    <div class="prose max-w-none">
        {!! $protocol->content !!}
    </div>

    {{-- Zurück-Button --}}
    <div class="mt-8">
        <a href="{{ route('protocols.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded hover:bg-gray-300">
            ← Zurück zur Übersicht
        </a>
    </div>
</div>
@endsection
