@extends('layouts.app')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Vorlagen</h1>

        <a href="{{ route('templates.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
            Neue Vorlage
        </a>
    </div>


    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif


    <div class="bg-white rounded shadow">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Name</th>
                    <th class="p-2 text-left">Typ</th>
                    <th class="p-2 text-right">Aktionen</th>
                </tr>
            </thead>

            <tbody>

            @forelse($templates as $t)

                <tr class="border-t">

                    <td class="p-2">
                        {{ $t->name }}
                    </td>

                    <td class="p-2">
                        {{ $t->type }}
                    </td>

                    <td class="p-2 text-right">

                        <a href="{{ route('templates.edit', $t->id) }}"
                           class="text-blue-600 mr-3">
                            Bearbeiten
                        </a>

                        <a href="{{ route('templates.preview', $t->id) }}"
                           class="text-green-600 mr-3">
                            Vorschau
                        </a>

                        <form method="POST"
                              action="{{ route('templates.destroy', $t->id) }}"
                              class="inline">

                            @csrf
                            @method('DELETE')

                            <button
                                onclick="return confirm('Vorlage löschen?')"
                                class="text-red-600">
                                Löschen
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="3" class="p-4 text-center text-gray-500">
                        Keine Vorlagen vorhanden
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection