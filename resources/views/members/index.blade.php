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
            <div class="overflow-auto bg-white rounded shadow">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase">
                        <tr>
                            @php
                                $headers = [
                                    'salutation' => 'Anrede',
                                    'first_name' => 'Vorname',
                                    'last_name' => 'Nachname',
                                    'email' => 'E-Mail',
                                    'mobile' => 'Mobil'
                                ];
                            @endphp

                            @foreach ($headers as $field => $label)
                                <th class="px-4 py-2 whitespace-nowrap">
                                    <a href="{{ route('members.index', array_merge(request()->except('page'), ['sort' => $field, 'direction' => ($sortField === $field && $sortDirection === 'asc') ? 'desc' : 'asc'])) }}"
                                        class="hover:underline {{ $sortField === $field ? 'font-bold text-blue-700' : '' }}">
                                        {{ $label }}
                                        @if ($sortField === $field)
                                            <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                            <th class="px-4 py-2 text-right">Aktion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($members as $member)
                            <tr>
                                <td class="px-4 py-2">{{ $member->salutation }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('members.show', $member) }}" class="text-blue-600 hover:underline">
                                        {{ $member->first_name }}
                                    </a>
                                </td>
                                <td class="px-4 py-2">{{ $member->last_name }}</td>
                                <td class="px-4 py-2">{{ $member->email ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $member->mobile ?? '—' }}</td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <a href="{{ route('members.show', $member) }}" class="text-blue-600 hover:underline">Anzeigen</a>
                                    <a href="{{ route('members.edit', $member) }}" class="text-yellow-600 hover:underline">Bearbeiten</a>
                                    <a href="{{ route('members.pdf', $member) }}" target="_blank" class="text-indigo-600 hover:underline">📄 Datenauskunft</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $members->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
