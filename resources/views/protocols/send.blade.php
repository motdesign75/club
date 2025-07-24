<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            📧 Protokoll versenden: {{ $protocol->title }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto bg-white shadow rounded space-y-6">

        {{-- Fehleranzeige --}}
        @if ($errors->any())
            <div class="text-sm text-red-700 bg-red-100 border border-red-200 p-4 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Info --}}
        <p class="text-sm text-gray-600">
            Bitte wähle aus, an welche Mitglieder das Protokoll <strong>„{{ $protocol->title }}“</strong> versendet werden soll.
        </p>

        <form method="POST" action="{{ route('protocols.mail.send', $protocol) }}">
            @csrf

            <div class="space-y-2 max-h-96 overflow-y-auto border border-gray-200 p-4 rounded bg-gray-50">
                @forelse ($members as $member)
                    <label class="flex items-center space-x-3 text-sm text-gray-800">
                        <input
                            type="checkbox"
                            name="emails[]"
                            value="{{ $member->email }}"
                            class="rounded text-blue-600 border-gray-300 focus:ring-blue-500"
                        >
                        <span>{{ $member->first_name }} {{ $member->last_name }} &lt;{{ $member->email }}&gt;</span>
                    </label>
                @empty
                    <p class="text-sm text-gray-500">Keine Mitglieder mit E-Mail-Adresse gefunden.</p>
                @endforelse
            </div>

            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('protocols.index') }}"
                   class="text-sm text-gray-600 hover:underline">
                    ⬅️ Zurück zur Übersicht
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                    📤 Protokoll jetzt senden
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
