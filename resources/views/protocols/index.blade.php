<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            📄 Protokolle
        </h2>
    </x-slot>

    <div class="p-6 max-w-6xl mx-auto space-y-6">

        {{-- Erfolgsmeldung --}}
        @if (session('success'))
            <div class="text-sm text-green-700 bg-green-100 border border-green-200 p-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Button: Neues Protokoll --}}
        <div class="flex justify-end">
            <a href="{{ route('protocols.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                ➕ Neues Protokoll
            </a>
        </div>

        {{-- Tabelle --}}
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full table-auto border border-gray-200">
                <thead class="bg-gray-100 text-sm text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">📅 Datum</th>
                        <th class="px-4 py-2 text-left">📝 Titel</th>
                        <th class="px-4 py-2 text-left">📌 Typ</th>
                        <th class="px-4 py-2 text-left">👤 Erstellt von</th>
                        <th class="px-4 py-2 text-right">⚙️ Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($protocols as $protocol)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-700">
                                {{ $protocol->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                {{ $protocol->title }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                {{ $protocol->type }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                {{ $protocol->user->name ?? 'Unbekannt' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-right space-x-3">
                                <a href="{{ route('protocols.show', $protocol) }}"
                                   class="text-blue-600 hover:underline">
                                    🔍 Ansehen
                                </a>
                                <a href="{{ route('protocols.edit', $protocol) }}"
                                   class="text-yellow-600 hover:underline">
                                    ✏️ Bearbeiten
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                Es wurden noch keine Protokolle erstellt.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
