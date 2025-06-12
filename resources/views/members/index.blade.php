@extends('layouts.sidebar')

@section('title', 'Mitglieder')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">👥 Mitgliederübersicht</h1>
        <a href="{{ route('members.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition focus:outline focus:ring-2 focus:ring-green-400">
            ➕ Neues Mitglied
        </a>
    </div>

    <!-- Suchformular -->
    <form method="GET" action="{{ route('members.index') }}" class="mb-6">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Mitglied suchen..." class="w-full md:w-1/3 px-4 py-2 border rounded shadow-sm focus:ring-2 focus:ring-blue-400">
    </form>

    @if($members->isEmpty())
        <p class="text-gray-500">Keine Mitglieder gefunden.</p>
    @else
        <div class="overflow-auto bg-white rounded shadow ring-1 ring-gray-200">
            <table class="min-w-full text-[15px] md:text-sm text-left">
                <thead class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase">
                    <tr>
                        <th class="px-3 py-3 sm:px-4 sm:py-2">Foto</th>
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
                            @php
                                $isHidden = in_array($field, ['email', 'mobile']) ? 'hidden md:table-cell' : '';
                            @endphp
                            <th class="px-3 py-3 sm:px-4 sm:py-2 whitespace-nowrap {{ $isHidden }}">
                                <a href="{{ route('members.index', array_merge(request()->except('page'), ['sort' => $field, 'direction' => ($sortField === $field && $sortDirection === 'asc') ? 'desc' : 'asc'])) }}"
                                   class="hover:underline {{ $sortField === $field ? 'font-bold text-blue-700' : '' }}">
                                    {{ $label }}
                                    @if ($sortField === $field)
                                        <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                        @endforeach
                        <th class="px-3 py-3 sm:px-4 sm:py-2 text-right">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50 transition">
                            <td class="px-3 py-3 sm:px-4 sm:py-2">
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" alt="Profilfoto von {{ $member->first_name }}"
                                         class="w-10 h-10 rounded-full object-cover" aria-hidden="true">
                                    <span class="sr-only">Foto vorhanden</span>
                                @else
                                    <span class="text-gray-400 italic">Kein Bild</span>
                                @endif
                            </td>

                            <td class="px-3 py-3 sm:px-4 sm:py-2">{{ $member->salutation }}</td>
                            <td class="px-3 py-3 sm:px-4 sm:py-2 break-words max-w-[160px]">
                                <a href="{{ route('members.show', $member) }}" class="text-blue-600 hover:underline">
                                    {{ $member->first_name }}
                                </a>
                            </td>
                            <td class="px-3 py-3 sm:px-4 sm:py-2 break-words max-w-[160px]">{{ $member->last_name }}</td>
                            <td class="px-3 py-3 sm:px-4 sm:py-2 hidden md:table-cell break-words max-w-[180px]">{{ $member->email ?? '—' }}</td>
                            <td class="px-3 py-3 sm:px-4 sm:py-2 hidden md:table-cell">{{ $member->mobile ?? '—' }}</td>
                            <td class="px-3 py-3 sm:px-4 sm:py-2 text-right">
                                <div class="flex justify-end items-center gap-3">
                                    {{-- Anzeigen --}}
                                    <a href="{{ route('members.show', $member) }}"
                                       class="inline-flex items-center text-blue-600 hover:text-blue-800 focus:outline focus:ring-2 focus:ring-blue-400 rounded"
                                       aria-label="Mitglied anzeigen"
                                       title="Anzeigen">
                                        <span aria-hidden="true">🔍</span>
                                        <span class="sr-only">Anzeigen</span>
                                    </a>

                                    {{-- Bearbeiten --}}
                                    <a href="{{ route('members.edit', $member) }}"
                                       class="inline-flex items-center text-yellow-600 hover:text-yellow-700 focus:outline focus:ring-2 focus:ring-yellow-400 rounded"
                                       aria-label="Mitglied bearbeiten"
                                       title="Bearbeiten">
                                        <span aria-hidden="true">✏️</span>
                                        <span class="sr-only">Bearbeiten</span>
                                    </a>

                                    {{-- PDF --}}
                                    <a href="{{ route('members.pdf', $member) }}"
                                       class="inline-flex items-center text-indigo-600 hover:text-indigo-800 focus:outline focus:ring-2 focus:ring-indigo-400 rounded"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       aria-label="Datenauskunft im PDF-Format"
                                       title="Datenauskunft">
                                        <span aria-hidden="true">📄</span>
                                        <span class="sr-only">Datenauskunft</span>
                                    </a>
                                </div>
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
