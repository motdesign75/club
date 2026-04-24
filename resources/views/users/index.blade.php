@extends('layouts.app')

@section('title', 'Benutzerverwaltung')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">👥 Benutzer</h1>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow">
            ➕ Neuer Benutzer
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden rounded-md">

        <table class="min-w-full divide-y divide-gray-200 text-sm">

            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-600">Name</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600">E-Mail</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600">Rolle</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-600">Erstellt am</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-600">Aktionen</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">

            @php
                $currentUser = auth()->user();
            @endphp

            @forelse($users as $user)

                @php
                    $isCurrent = $currentUser && $currentUser->id == $user->id;
                @endphp

                <tr class="{{ $isCurrent ? 'bg-green-50 border-l-4 border-green-500' : '' }}">

                    {{-- Name --}}
                    <td class="px-4 py-2 font-medium text-gray-800">
                        {{ $user->name }}

                        @if($isCurrent)
                            <span class="ml-2 text-xs text-green-600 font-semibold">
                                (Du)
                            </span>
                        @endif
                    </td>

                    {{-- Email --}}
                    <td class="px-4 py-2">
                        {{ $user->email }}
                    </td>

                    {{-- Rolle --}}
                    <td class="px-4 py-2">
                        {{ $user->role ?? '—' }}
                    </td>

                    {{-- Datum --}}
                    <td class="px-4 py-2">
                        {{ $user->created_at?->format('d.m.Y') }}
                    </td>

                    {{-- Aktionen --}}
                    <td class="px-4 py-2 text-right">

                        @if(!$isCurrent)

                            <form action="{{ route('users.destroy', $user) }}"
                                  method="POST"
                                  onsubmit="return confirm('Benutzer wirklich löschen?');"
                                  class="inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="text-red-600 hover:underline text-sm">
                                    🗑️ Löschen
                                </button>

                            </form>

                        @else

                            <span class="inline-flex items-center text-green-600 text-sm font-medium">
                                🟢 Aktuell eingeloggt
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                        Keine Benutzer vorhanden.
                    </td>
                </tr>

            @endforelse

            </tbody>
        </table>

    </div>

</div>
@endsection