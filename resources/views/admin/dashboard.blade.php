@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-6xl mx-auto py-10 space-y-8">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600">Übersicht über alle Registrierungen</p>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white p-6 rounded-2xl shadow">
            <p class="text-sm text-gray-500">Vereine gesamt</p>
            <p class="text-3xl font-bold mt-2">{{ $totalTenants }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow">
            <p class="text-sm text-gray-500">Benutzer gesamt</p>
            <p class="text-3xl font-bold mt-2">{{ $totalUsers }}</p>
        </div>

    </div>

    {{-- NEUE VEREINE --}}
    <div class="bg-white rounded-2xl shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold">Neueste Vereine</h2>
        </div>

        <table class="w-full text-sm">
    <thead class="bg-gray-50 text-left">
        <tr>
            <th class="p-3">Name</th>
            <th class="p-3">E-Mail</th>
            <th class="p-3">Erstellt am</th>
            <th class="p-3">Aktion</th>
        </tr>
    </thead>
    <tbody>
        @forelse($latestTenants as $tenant)
            <tr class="border-t">
                <td class="p-3 font-medium">{{ $tenant->name ?? '-' }}</td>

                <td class="p-3">
                    {{ $tenant->email ?? '-' }}
                </td>

                <td class="p-3">
                    {{ optional($tenant->created_at)->format('d.m.Y H:i') }}
                </td>

                <td class="p-3">
                    @if($tenant->email)
                        <a href="mailto:{{ $tenant->email }}?subject=Willkommen bei Clubano&body=Hallo {{ urlencode($tenant->name) }},%0D%0A%0D%0Avielen Dank für Ihre Registrierung bei Clubano.%0D%0A%0D%0AWenn Sie Fragen haben oder Unterstützung beim Einrichten benötigen, stehe ich Ihnen gerne persönlich zur Verfügung.%0D%0A%0D%0AViele Grüße%0D%0AMaik-Oliver Towet"
                           class="inline-block bg-blue-600 text-white text-xs px-3 py-2 rounded-lg hover:bg-blue-700">
                            Kontakt aufnehmen
                        </a>
                    @else
                        <span class="text-gray-400 text-xs">keine E-Mail</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-4 text-gray-500 text-center">
                    Noch keine Registrierungen vorhanden
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
    </div>

    {{-- NEUE USER --}}
    <div class="bg-white rounded-2xl shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold">Neueste Benutzer</h2>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="p-3">Name</th>
                    <th class="p-3">E-Mail</th>
                    <th class="p-3">Erstellt am</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestUsers as $user)
                    <tr class="border-t">
                        <td class="p-3 font-medium">{{ $user->name ?? '-' }}</td>
                        <td class="p-3">{{ $user->email ?? '-' }}</td>
                        <td class="p-3">
                            {{ optional($user->created_at)->format('d.m.Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-gray-500 text-center">
                            Noch keine Benutzer vorhanden
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection