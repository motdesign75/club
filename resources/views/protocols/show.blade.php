@extends('layouts.app')

@section('title', 'Protokoll anzeigen')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white p-6 shadow rounded">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ $protocol->title }}
        </h2>

        <div class="mt-3 text-sm text-gray-600 space-y-1">
            <p><strong>Typ:</strong> {{ $protocol->type }}</p>
            <p><strong>Erstellt am:</strong> {{ $protocol->created_at->format('d.m.Y H:i') }}</p>

            @if($protocol->location)
                <p><strong>Ort:</strong> {{ $protocol->location }}</p>
            @endif

            @if($protocol->start_time)
                <p><strong>Beginn:</strong> {{ $protocol->start_time }}</p>
            @endif

            @if($protocol->end_time)
                <p><strong>Ende:</strong> {{ $protocol->end_time }}</p>
            @endif
        </div>
    </div>


    {{-- TEILNEHMER --}}
    @if($protocol->participants && $protocol->participants->count() > 0)
        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-lg font-semibold mb-3">👥 Teilnehmer</h3>

            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-gray-700 text-sm">
                @foreach ($protocol->participants as $member)
                    <li class="bg-gray-50 px-3 py-2 rounded">
                        {{ $member->full_name }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- BESCHLÜSSE --}}
    @if($protocol->resolutions)
        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-lg font-semibold mb-3">📌 Beschlüsse / Ergebnisse</h3>

            <div class="text-gray-700 whitespace-pre-line">
                {{ $protocol->resolutions }}
            </div>
        </div>
    @endif


    {{-- NÄCHSTES TREFFEN --}}
    @if($protocol->next_meeting)
        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-lg font-semibold mb-3">📅 Nächstes Treffen</h3>

            <div class="text-gray-700 whitespace-pre-line">
                {{ $protocol->next_meeting }}
            </div>
        </div>
    @endif


    {{-- HAUPTINHALT --}}
    <div class="bg-white p-6 shadow rounded">
        <h3 class="text-lg font-semibold mb-3">📝 Protokoll</h3>

        <div class="prose max-w-none">
            {!! $protocol->content !!}
        </div>
    </div>


    {{-- ANHÄNGE --}}
    @php
        $attachments = $protocol->attachments ?? $protocol->attachment_paths ?? [];
        if (is_string($attachments)) {
            $attachments = [$attachments];
        }
    @endphp

    @if(!empty($attachments))
        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-lg font-semibold mb-3">📎 Anhänge</h3>

            <ul class="space-y-2">
                @foreach($attachments as $file)
                    <li>
                        <a href="{{ asset('storage/'.$file) }}"
                           target="_blank"
                           class="flex items-center gap-2 text-blue-600 hover:underline text-sm">

                            📄 {{ basename($file) }}

                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- FOOTER --}}
    <div class="flex justify-between items-center">

        <a href="{{ route('protocols.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded hover:bg-gray-300">
            ← Zurück zur Übersicht
        </a>

        <div class="flex gap-2">

            <a href="{{ route('protocols.edit', $protocol) }}"
               class="px-4 py-2 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                ✏️ Bearbeiten
            </a>

            <a href="{{ route('protocols.mail.form', $protocol) }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                📧 Versenden
            </a>

        </div>

    </div>

</div>
@endsection