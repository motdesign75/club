@extends('layouts.sidebar')

@section('title', 'Dashboard')

@section('content')

@php
    \Carbon\Carbon::setLocale('de');
@endphp

<div class="space-y-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mt-4">
        👋 Willkommen, {{ Auth::user()->name }}!
    </h1>

    {{-- Statistikkacheln --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mt-6">

        {{-- Mitglieder --}}
        <a href="{{ route('members.index') }}" class="flex items-center space-x-4 bg-white shadow rounded-xl p-4 hover:bg-blue-50 transition group">
            <div class="bg-blue-100 text-blue-700 rounded-full p-3 text-xl">👥</div>
            <div>
                <p class="text-sm text-gray-500">Mitglieder</p>
                <p class="text-2xl font-bold text-gray-800 group-hover:text-blue-700">{{ $membersCount }}</p>
            </div>
        </a>

        {{-- Neue Mitglieder im Monat --}}
        <a href="{{ route('members.index') }}" class="flex items-center space-x-4 bg-white shadow rounded-xl p-4 hover:bg-green-50 transition group">
            <div class="bg-green-100 text-green-700 rounded-full p-3 text-xl">➕</div>
            <div>
                <p class="text-sm text-gray-500">Neue im {{ now()->translatedFormat('F') }}</p>
                <p class="text-2xl font-bold text-gray-800 group-hover:text-green-700">{{ $entries->count() }}</p>
            </div>
        </a>

        {{-- Lizenztyp --}}
        <div class="flex items-center space-x-4 bg-white shadow rounded-xl p-4">
            <div class="bg-yellow-100 text-yellow-700 rounded-full p-3 text-xl">🔐</div>
            <div>
                <p class="text-sm text-gray-500">Lizenz</p>
                <p class="text-2xl font-bold text-gray-800">{{ $licenseType }}</p>
            </div>
        </div>

    </div>

    {{-- Vereinsaktivitäten Tabs --}}
    <div class="bg-white rounded-xl shadow-md p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 Vereinsaktivitäten im {{ now()->translatedFormat('F') }}</h2>
        <livewire:dashboard-member-stats />
    </div>

    {{-- Mitgliederentwicklung Chart --}}
    <div class="bg-white rounded-xl shadow-md p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">📈 Mitgliederentwicklung {{ now()->year }}</h2>
        <livewire:dashboard-member-chart />
    </div>

    {{-- Timeline – Nächste 7 Tage --}}
    <div class="bg-white rounded-xl shadow-md p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">🕒 Nächste Termine (7 Tage)</h2>

        @forelse ($timeline as $event)
            <div class="flex justify-between items-start border-b py-3 text-sm">
                <div class="space-y-0.5">
                    <p class="font-semibold text-gray-900">{{ $event->title }}</p>
                    <p class="text-gray-600">
                        {{ \Carbon\Carbon::parse($event->start)->translatedFormat('d.m.Y H:i') }}
                        – {{ \Carbon\Carbon::parse($event->end)->format('H:i') }}
                        @if ($event->location)
                            · {{ $event->location }}
                        @endif
                    </p>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full mt-1
                    {{ $event->is_public ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $event->is_public ? 'Öffentlich' : 'Intern' }}
                </span>
            </div>
        @empty
            <p class="text-gray-500">Keine Termine in den nächsten 7 Tagen.</p>
        @endforelse
    </div>
</div>

@endsection
