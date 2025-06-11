<x-app-layout>
    <x-slot name="header">Mitglieder-Import</x-slot>

    <form method="POST" action="{{ route('import.mitglieder.preview') }}" enctype="multipart/form-data">
        @csrf
        <div class="p-4 bg-white rounded shadow">
            <label class="block mb-2 font-semibold">CSV-Datei auswählen</label>
            <input type="file" name="csv_file" accept=".csv" required class="mb-4">

            <x-primary-button>Vorschau anzeigen</x-primary-button>
        </div>
    </form>
</x-app-layout>
