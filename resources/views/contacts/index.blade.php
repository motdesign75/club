@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                Kontakte
            </h2>

            @can('create', App\Models\Contact::class)
                <a href="{{ route('contacts.create') }}"
                   class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                    Neuer Kontakt
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter --}}
        <form method="GET"
              action="{{ route('contacts.index') }}"
              class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex flex-col gap-3 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">
                        Kontakte suchen und filtern
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $contacts->total() }} Kontakt{{ $contacts->total() === 1 ? '' : 'e' }} gefunden
                    </p>
                </div>

                @can('create', App\Models\Contact::class)
                    <a href="{{ route('contacts.create') }}"
                       class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        Neuer Kontakt
                    </a>
                @endcan
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-6">

                <div class="lg:col-span-2">
                    <label for="q" class="block text-sm font-medium text-gray-700">
                        Suche
                    </label>
                    <input type="text"
                           name="q"
                           id="q"
                           value="{{ $q }}"
                           placeholder="Name, Firma, E-Mail, Ort ..."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="contact_type" class="block text-sm font-medium text-gray-700">
                        Typ
                    </label>
                    <select name="contact_type"
                            id="contact_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Alle</option>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" @selected($contactType === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">
                        Kategorie
                    </label>
                    <select name="category"
                            id="category"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Alle</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" @selected($category === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">
                        Status
                    </label>
                    <select name="status"
                            id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Alle</option>
                        <option value="active" @selected($status === 'active')>Aktiv</option>
                        <option value="inactive" @selected($status === 'inactive')>Inaktiv</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <label class="inline-flex items-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700">
                        <input type="checkbox"
                               name="favorites"
                               value="1"
                               @checked($favorites)
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Favoriten
                    </label>
                </div>
            </div>

            <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('contacts.index') }}"
                   class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Zurücksetzen
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                    Filtern
                </button>
            </div>
        </form>

        {{-- Liste --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Kontakt
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Kategorie
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Kommunikation
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Ort
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Status
                            </th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Aktionen
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($contacts as $contact)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 align-top">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                            {{ mb_substr($contact->display_name, 0, 1) }}
                                        </div>

                                        <div>
                                            <div class="font-semibold text-gray-900">
                                                @if($contact->is_favorite)
                                                    <span title="Favorit">★</span>
                                                @endif

                                                <a href="{{ route('contacts.show', $contact) }}"
                                                   class="hover:text-indigo-700">
                                                    {{ $contact->display_name }}
                                                </a>
                                            </div>

                                            <div class="mt-1 text-xs text-gray-500">
                                                {{ $types[$contact->contact_type] ?? ucfirst($contact->contact_type ?? 'Kontakt') }}

                                                @if($contact->position)
                                                    · {{ $contact->position }}
                                                @endif

                                                @if($contact->department)
                                                    · {{ $contact->department }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 align-top text-sm text-gray-700">
                                    @if($contact->category)
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                            {{ $categories[$contact->category] ?? ucfirst($contact->category) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 align-top text-sm text-gray-700">
                                    <div class="space-y-1">
                                        @if($contact->primary_email)
                                            <div>
                                                <a href="mailto:{{ $contact->primary_email }}"
                                                   class="text-indigo-600 hover:text-indigo-800">
                                                    {{ $contact->primary_email }}
                                                </a>
                                            </div>
                                        @endif

                                        @if($contact->primary_phone)
                                            <div>
                                                <a href="tel:{{ $contact->primary_phone }}"
                                                   class="text-gray-700 hover:text-indigo-700">
                                                    {{ $contact->primary_phone }}
                                                </a>
                                            </div>
                                        @endif

                                        @if(!$contact->primary_email && !$contact->primary_phone)
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 py-4 align-top text-sm text-gray-700">
                                    @if($contact->city || $contact->zip)
                                        {{ trim(($contact->zip ?? '') . ' ' . ($contact->city ?? '')) }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 align-top">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                        {{ $contact->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $contact->status_label }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 align-top text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        @can('view', $contact)
                                            <a href="{{ route('contacts.show', $contact) }}"
                                               class="text-indigo-600 hover:text-indigo-900">
                                                Anzeigen
                                            </a>
                                        @endcan

                                        @can('update', $contact)
                                            <a href="{{ route('contacts.edit', $contact) }}"
                                               class="text-gray-600 hover:text-gray-900">
                                                Bearbeiten
                                            </a>
                                        @endcan

                                        @can('delete', $contact)
                                            <form method="POST"
                                                  action="{{ route('contacts.destroy', $contact) }}"
                                                  onsubmit="return confirm('Kontakt wirklich löschen?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900">
                                                    Löschen
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="mx-auto max-w-md">
                                        <h3 class="text-base font-semibold text-gray-900">
                                            Keine Kontakte gefunden
                                        </h3>

                                        <p class="mt-2 text-sm text-gray-500">
                                            Lege deinen ersten Kontakt an oder passe die Filter an.
                                        </p>

                                        <div class="mt-5 flex flex-col justify-center gap-2 sm:flex-row">
                                            <a href="{{ route('contacts.index') }}"
                                               class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                Filter zurücksetzen
                                            </a>

                                            @can('create', App\Models\Contact::class)
                                                <a href="{{ route('contacts.create') }}"
                                                   class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                                                    Kontakt erstellen
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($contacts->hasPages())
                <div class="border-t border-gray-200 px-4 py-3">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
