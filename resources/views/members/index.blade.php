@extends('layouts.app')

@section('title', 'Mitglieder')

@section('content')
<div class="space-y-6">
    <!-- Titel & Button -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h1 class="text-3xl font-extrabold text-gray-800">👥 Mitgliederübersicht</h1>
        <a href="{{ route('members.create') }}"
           class="bg-[#2954A3] text-white px-5 py-2 rounded-xl shadow hover:bg-[#1E3F7F] transition-all">
            ➕ Neues Mitglied
        </a>
    </div>

    <!-- Status-Tabs -->
    <div class="mt-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-6 overflow-x-auto">
            @php
                $statuses = [
                    '' => 'Alle',
                    'aktiv' => 'Aktiv',
                    'ehemalig' => 'Ehemalig',
                    'zukünftig' => 'Zukünftig'
                ];
                $currentStatus = request('status', '');
            @endphp
            @foreach($statuses as $key => $label)
                <a href="{{ route('members.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                   class="whitespace-nowrap px-3 py-2 font-medium text-sm border-b-2 {{ $currentStatus === $key ? 'border-[#2954A3] text-[#2954A3]' : 'border-transparent text-gray-500 hover:text-[#2954A3]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Tag-Filter -->
    @if($allTags->isNotEmpty())
    <form method="GET" action="{{ route('members.index') }}" class="mt-4">
        <select name="tag" onchange="this.form.submit()"
                class="px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#2954A3] focus:outline-none">
            <option value="">🔖 Alle Tags</option>
            @foreach($allTags as $tag)
                <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>
    @endif

    <!-- Suchleiste -->
    <form method="GET" action="{{ route('members.index') }}" class="mt-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Mitglied suchen..."
               class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#2954A3] focus:outline-none">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="tag" value="{{ request('tag') }}">
    </form>

    @if($members->isEmpty())
        <p class="text-gray-500 mt-6">Keine Mitglieder gefunden.</p>
    @else
        <!-- Massenverarbeitung Formular -->
        <form method="POST" action="{{ route('members.bulk-action') }}">
            @csrf

            <div class="mt-4 mb-2 flex items-center gap-4">
                <select name="action" required class="rounded border-gray-300 px-3 py-2">
                    <option value="">Aktion wählen</option>
                    <option value="set_status_aktiv">Status: Aktiv</option>
                    <option value="set_status_passiv">Status: Passiv</option>
                    <option value="set_status_ehemalig">Status: Ehemalig</option>
                    <option value="delete">Löschen</option>
                </select>

                <x-primary-button>Ausführen</x-primary-button>
            </div>

            <!-- Tabelle -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-sm rounded-xl overflow-hidden">
                    <thead class="bg-gray-100 text-gray-600 text-left text-sm uppercase">
                        <tr>
                            <th class="px-6 py-3"><input type="checkbox" id="checkAll"></th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Tags</th>
                            <th class="px-6 py-3 text-right">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($members as $member)
                            <tr class="border-b">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="member_ids[]" value="{{ $member->id }}" class="member-checkbox">
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('members.show', $member) }}" class="font-medium text-[#2954A3] hover:underline">
                                        {{ $member->fullname }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match($member->status){
                                            'aktiv' => 'green',
                                            'ehemalig' => 'gray',
                                            'zukünftig' => 'blue',
                                            default => 'yellow'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @foreach($member->tags as $tag)
                                        <span class="inline-block text-white text-xs px-2 py-1 rounded mr-1 mb-1"
                                              style="background-color: {{ $tag->color ?? '#6b7280' }}">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('members.edit', $member) }}" class="text-sm text-blue-600 hover:underline">Bearbeiten</a>
                                    <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Wirklich löschen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline">Löschen</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $members->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.getElementById('checkAll').addEventListener('change', function () {
        document.querySelectorAll('.member-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
</script>
@endpush
@endsection
