@extends('layouts.app')

@section('title','Dashboard')

@section('content')

@php
\Carbon\Carbon::setLocale('de');

$tenant = Auth::user()->tenant;

$subscription = $tenant?->subscription('default');
$isSubscribed = (bool) ($tenant && $tenant->subscribed('default'));

$endsAt = $subscription?->ends_at;

@endphp


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">


{{-- ===============================
HERO
=============================== --}}

<div class="rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white p-8 shadow-lg">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

        <div>

            <h1 class="text-3xl font-bold">
                Willkommen, {{ Auth::user()->name }}
            </h1>

            <p class="text-indigo-100 mt-1">
                Dein Verein im Überblick – alles Wichtige auf einen Blick.
            </p>

        </div>


        <div class="flex gap-3">

            <a href="{{ route('members.create') }}"
               class="bg-white text-indigo-700 px-4 py-2 rounded-xl font-semibold shadow hover:bg-indigo-50">

                Mitglied hinzufügen

            </a>

            <a href="{{ route('events.create') }}"
               class="bg-indigo-900 px-4 py-2 rounded-xl font-semibold shadow hover:bg-indigo-800">

                Termin anlegen

            </a>

        </div>

    </div>

</div>



{{-- ===============================
KPI CARDS
=============================== --}}

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">


{{-- Mitglieder --}}

<div class="bg-white rounded-3xl shadow p-6">

    <p class="text-gray-500 text-sm">
        Mitglieder
    </p>

    <p class="text-4xl font-bold text-gray-900 mt-2">
        {{ $membersCount }}
    </p>

    <p class="text-xs text-gray-400 mt-2">
        Gesamt im Verein
    </p>

</div>



{{-- Neue --}}

<div class="bg-white rounded-3xl shadow p-6">

    <p class="text-gray-500 text-sm">
        Neue diesen Monat
    </p>

    <p class="text-4xl font-bold text-green-600 mt-2">
        {{ $entries->count() }}
    </p>

    <p class="text-xs text-gray-400 mt-2">
        {{ now()->translatedFormat('F Y') }}
    </p>

</div>



{{-- Lizenzstatus --}}

<div class="bg-white rounded-3xl shadow p-6">

    <p class="text-gray-500 text-sm">
        Lizenzstatus
    </p>

    @php
        $tenant = auth()->user()->tenant;
        $onTrial = $tenant && $tenant->onTrial();
        $trialEndsAt = $tenant?->trial_ends_at;
    @endphp

    @if($tenant && $tenant->subscribed('default') && ! $onTrial)
        <p class="text-2xl font-bold mt-2 text-green-600">
            Aktiv
        </p>

    @elseif($onTrial)
        <p class="text-2xl font-bold mt-2 text-yellow-600">
            Testphase
        </p>

        <p class="text-sm text-gray-600 mt-2">
            endet am {{ $trialEndsAt?->format('d.m.Y') }}
        </p>

    @else
        <p class="text-2xl font-bold mt-2 text-red-600">
            Keine Lizenz
        </p>
    @endif

</div>

</div>

{{-- Haupt CTA Lizenz --}}

<div class="bg-blue-600 text-white rounded-3xl shadow p-6 flex flex-col justify-between">

    <div>
        <p class="text-sm opacity-80">
            Clubano freischalten
        </p>

        <p class="text-xl font-bold mt-2">
            7 Tage kostenlos testen
        </p>

        <p class="text-sm mt-2 opacity-90">
            Danach nur 19,99 € / Monat
        </p>
    </div>

    <a href="{{ route('subscription.index') }}"
       class="mt-6 bg-white text-blue-600 text-sm px-4 py-2 rounded-lg font-semibold text-center hover:bg-gray-100">
        Jetzt starten
    </a>

</div>







</div>



{{-- ===============================
2 SPALTEN LAYOUT
=============================== --}}

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">



{{-- CHART --}}

<div class="xl:col-span-2 bg-white rounded-3xl shadow p-6">

    <h2 class="text-lg font-semibold mb-4">
        Mitgliederentwicklung
    </h2>

    @livewire('dashboard-member-chart')

</div>



{{-- TERMINE --}}

<div class="bg-white rounded-3xl shadow p-6">

    <h2 class="text-lg font-semibold mb-4">
        Nächste Termine
    </h2>


    <div class="space-y-4">

        @forelse($timeline as $event)

            <div class="border rounded-xl p-3">

                <p class="font-semibold">
                    {{ $event->title }}
                </p>

                <p class="text-sm text-gray-500">

                    {{ \Carbon\Carbon::parse($event->start)->translatedFormat('d.m.Y H:i') }}

                </p>

            </div>

        @empty

            <p class="text-gray-400">
                Keine Termine
            </p>

        @endforelse

    </div>

</div>



</div>



{{-- ===============================
AKTIVITÄT
=============================== --}}

<div class="bg-white rounded-3xl shadow p-6">

    <h2 class="text-lg font-semibold mb-4">
        Aktivität im Verein
    </h2>

    @livewire('dashboard-member-stats')

</div>



</div>

@endsection