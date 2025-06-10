@extends('layouts.sidebar')

@section('title', 'Mitglieder')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">👥 Mitgliederübersicht</h1>
            <a href="{{ route('members.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                ➕ Neues Mitglied
            </a>
        </div>

        <!-- Suchformular -->
        <form method="GET" action="{{ route('members.index') }}" class="mb-6">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Mitglied suchen..." class="w-full md:w-1/3 px-4 py-2 border rounded shadow-sm">
        </form>

        @if($members->isEmpty())
            <p class="text-gray-500">Keine Mitglieder gefunden.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($members as $member)
                    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                        <h2 class="text-xl font-semibold text-gray-800 mb-1">
                            {{ $member->salutation }} {{ $member->title }} {{ $member->first_name }} {{ $member->last_name }}
                        </h2>
                        @if($member->company)
                            <p class="text-sm text-gray-500 italic mb-1">🏢 {{ $member->company }}</p>
                        @endif
                        <p class="text-sm text-gray-700 mb-1">✉️ {{ $member->email ?? '—' }}</p>
                        <p class="text-sm text-gray-700 mb-3">📱 {{ $member->mobile ?? '—' }}</p>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('members.show', $member) }}" class="text-blue-600 hover:underline">Anzeigen</a>
                            <a href="{{ route('members.edit', $member) }}" class="text-yellow-600 hover:underline">Bearbeiten</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
