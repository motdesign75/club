@php
    $isEdit = isset($event);
@endphp

<form method="POST" action="{{ $isEdit ? route('events.update', $event) : route('events.store') }}" class="space-y-8">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <!-- Block: Veranstaltung -->
    <div class="bg-white p-6 rounded shadow space-y-4">
        <h2 class="text-xl font-semibold text-gray-700">📅 Veranstaltung</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="title" class="block text-sm font-medium">Titel</label>
                <input type="text" name="title" id="title" value="{{ old('title', $event->title ?? '') }}"
                       class="w-full border-gray-300 rounded" required>
            </div>

            <div>
                <label for="location" class="block text-sm font-medium">Ort</label>
                <input type="text" name="location" id="location" value="{{ old('location', $event->location ?? '') }}"
                       class="w-full border-gray-300 rounded">
            </div>

            <div>
                <label for="start" class="block text-sm font-medium">Beginn</label>
                <input type="datetime-local" name="start" id="start"
                       value="{{ old('start', isset($event) ? $event->start->format('Y-m-d\TH:i') : '') }}"
                       class="w-full border-gray-300 rounded" required>
            </div>

            <div>
                <label for="end" class="block text-sm font-medium">Ende</label>
                <input type="datetime-local" name="end" id="end"
                       value="{{ old('end', isset($event) ? $event->end->format('Y-m-d\TH:i') : '') }}"
                       class="w-full border-gray-300 rounded" required>
            </div>

            <div>
                <label for="is_public" class="block text-sm font-medium">Sichtbarkeit</label>
                <select name="is_public" id="is_public" class="w-full border-gray-300 rounded">
                    <option value="1" {{ old('is_public', $event->is_public ?? '') == 1 ? 'selected' : '' }}>Öffentlich</option>
                    <option value="0" {{ old('is_public', $event->is_public ?? '') == 0 ? 'selected' : '' }}>Intern</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Block: Beschreibung -->
    <div class="bg-white p-6 rounded shadow space-y-4">
        <h2 class="text-xl font-semibold text-gray-700">📝 Beschreibung</h2>
        <div>
            <label for="description" class="block text-sm font-medium">Details</label>
            <textarea name="description" id="description" rows="5"
                      class="w-full border-gray-300 rounded">{{ old('description', $event->description ?? '') }}</textarea>
        </div>
    </div>

    <!-- Speichern -->
    <div class="text-right">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            💾 {{ $isEdit ? 'Aktualisieren' : 'Speichern' }}
        </button>
    </div>
</form>
