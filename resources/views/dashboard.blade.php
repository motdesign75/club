@extends('layouts.sidebar')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">👋 Willkommen, {{ Auth::user()->name }}!</h1>

        <p class="text-gray-600">Du bist im Verwaltungsbereich für:</p>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
            <h2 class="text-xl font-semibold text-indigo-600 mb-2">🏢 Verein: {{ $tenant->name }}</h2>
            <p class="text-gray-700">
                <strong>Email:</strong> {{ $tenant->email }}<br>
                <strong>Adresse:</strong> {{ $tenant->address }}, {{ $tenant->zip }} {{ $tenant->city }}<br>
                <strong>Telefon:</strong> {{ $tenant->phone }}<br>
                <strong>Register-Nr.:</strong> {{ $tenant->register_number }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-8">
            <!-- Mitgliederanzahl -->
            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-500">
                <div class="text-sm text-gray-600">👥 Mitglieder</div>
                <div class="text-3xl font-bold text-gray-800 mt-2">
                    {{ \App\Models\Member::where('tenant_id', $tenant->id)->count() }}
                </div>
            </div>

            <!-- Lizenzstatus -->
            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500">
                <div class="text-sm text-gray-600">📄 Lizenzstatus</div>
                <div class="text-xl font-semibold text-gray-800 mt-2">
                    Trial-Version ({{ \Carbon\Carbon::parse($tenant->created_at)->addWeeks(4)->format('d.m.Y') }} endet)
                </div>
            </div>

            <!-- Platzhalter für zukünftiges Feature -->
            <!-- Nächste Veranstaltungen -->
<div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-400">
    <div class="text-sm text-gray-600 mb-2">📅 Nächste Veranstaltungen</div>

    @if(isset($events) && $events->count())
        <ul class="space-y-2">
            @foreach($events as $event)
                <li class="border-t pt-2">
                    <div class="text-base font-semibold text-gray-800">{{ $event->title }}</div>
                    <div class="text-sm text-gray-600">
                        {{ $event->start->format('d.m.Y H:i') }} – {{ $event->end->format('d.m.Y H:i') }}<br>
                        @if($event->is_public)
                            <span class="text-green-600 text-xs font-medium">Öffentlich</span>
                        @else
                            <span class="text-yellow-600 text-xs font-medium">Intern</span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-gray-500 text-sm">Aktuell keine geplanten Events.</div>
    @endif
</div>

@endsection
