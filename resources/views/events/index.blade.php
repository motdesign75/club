<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">📅 Veranstaltungen</h2>
    </x-slot>

    <div class="py-6">
        <a href="{{ route('events.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
            ➕ Neues Event
        </a>

        @if(session('success'))
            <div class="text-green-600 mt-4">{{ session('success') }}</div>
        @endif

        <div class="mt-6">
            @foreach($events as $event)
                <div class="border p-4 rounded mb-4 bg-white">
                    <div class="flex justify-between">
                        <div>
                            <h3 class="text-xl font-bold">{{ $event->title }}</h3>
                            <p>{{ $event->start->format('d.m.Y H:i') }} – {{ $event->end->format('d.m.Y H:i') }}</p>
                            <p>{{ $event->location }}</p>
                        </div>
                        <div class="space-x-2">
                            <a href="{{ route('events.edit', $event) }}" class="text-blue-600 hover:underline">Bearbeiten</a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline" onclick="return confirm('Wirklich löschen?')">Löschen</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
