<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">✏️ Event bearbeiten</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <form action="{{ route('events.update', $event) }}" method="POST">
            @csrf
            @method('PUT')
            @include('events.form', ['event' => $event])
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Aktualisieren</button>
        </form>
    </div>
</x-app-layout>
