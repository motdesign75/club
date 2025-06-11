<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">➕ Neues Event erstellen</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <form action="{{ route('events.store') }}" method="POST">
            @csrf
            @include('events.form')
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Speichern</button>
        </form>
    </div>
</x-app-layout>
