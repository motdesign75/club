@extends('layouts.sidebar')

@section('title', 'Neues Mitglied anlegen')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">➕ Neues Mitglied</h1>

    <form action="{{ route('members.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- Mitglied --}}
        <div class="bg-white p-6 rounded shadow space-y-4">
            <h2 class="text-xl font-semibold text-gray-700">🧍 Mitglied</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block text-sm font-medium">Geschlecht</label>
                    <select name="gender" id="gender" class="w-full border-gray-300 rounded">
                        <option value="">Bitte wählen</option>
                        <option value="weiblich">weiblich</option>
                        <option value="männlich">männlich</option>
                        <option value="divers">divers</option>
                    </select>
                </div>

                <div>
                    <label for="salutation" class="block text-sm font-medium">Anrede</label>
                    <select name="salutation" id="salutation" class="w-full border-gray-300 rounded">
                        <option value="">Bitte wählen</option>
                        <option value="Frau">Frau</option>
                        <option value="Herr">Herr</option>
                        <option value="Liebe">Liebe</option>
                        <option value="Lieber">Lieber</option>
                        <option value="Hallo">Hallo</option>
                    </select>
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium">Titel</label>
                    <input type="text" name="title" id="title" class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label for="first_name" class="block text-sm font-medium">Vorname</label>
                    <input type="text" name="first_name" id="first_name" class="w-full border-gray-300 rounded" required>
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium">Nachname</label>
                    <input type="text" name="last_name" id="last_name" class="w-full border-gray-300 rounded" required>
                </div>

                <div>
                    <label for="organization" class="block text-sm font-medium">Firma / Organisation</label>
                    <input type="text" name="organization" id="organization" class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label for="birthday" class="block text-sm font-medium">Geburtstag</label>
                    <input type="date" name="birthday" id="birthday" class="w-full border-gray-300 rounded">
                </div>
            </div>
        </div>

        {{-- Mitgliedschaft --}}
        <div class="bg-white p-6 rounded shadow space-y-4">
            <h2 class="text-xl font-semibold text-gray-700">📝 Mitgliedschaft</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="member_id" class="block text-sm font-medium">Mitgliedsnummer</label>
                    <input type="text" name="member_id" id="member_id" class="w-full border-gray-300 rounded">
                </div>
                <div>
                    <label for="entry_date" class="block text-sm font-medium">Eintritt</label>
                    <input type="date" name="entry_date" id="entry_date" class="w-full border-gray-300 rounded">
                </div>
                <div>
                    <label for="exit_date" class="block text-sm font-medium">Austritt</label>
                    <input type="date" name="exit_date" id="exit_date" class="w-full border-gray-300 rounded">
                </div>
                <div>
                    <label for="termination_date" class="block text-sm font-medium">Kündigungsdatum</label>
                    <input type="date" name="termination_date" id="termination_date" class="w-full border-gray-300 rounded">
                </div>
            </div>
        </div>

        {{-- Kommunikation --}}
        <div class="bg-white p-6 rounded shadow space-y-4">
            <h2 class="text-xl font-semibold text-gray-700">📞 Kommunikation</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-medium">E-Mail</label>
                    <input type="email" name="email" id="email" class="w-full border-gray-300 rounded">
                </div>
                <div>
                    <label for="mobile" class="block text-sm font-medium">Mobilfunknummer</label>
                    <input type="text" name="mobile" id="mobile" class="w-full border-gray-300 rounded">
                </div>
                <div>
                    <label for="landline" class="block text-sm font-medium">Festnetznummer</label>
                    <input type="text" name="landline" id="landline" class="w-full border-gray-300 rounded">
                </div>
            </div>
        </div>

        {{-- Adresse --}}
        <div class="bg-white p-6 rounded shadow space-y-4">
            <h2 class="text-xl font-semibold text-gray-700">📍 Adresse</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="street" class="block text-sm font-medium">Straße + Nr.</label>
                    <input type="text" name="street" id="street" class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label for="address_addition" class="block text-sm font-medium">Adresszusatz</label>
                    <input type="text" name="address_addition" id="address_addition" class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label for="zip" class="block text-sm font-medium">PLZ</label>
                    <input type="text" name="zip" id="zip" class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium">Ort</label>
                    <input type="text" name="city" id="city" class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium">Land</label>
                    <select name="country" id="country" class="w-full border-gray-300 rounded">
                        @foreach (config('countries.list') as $code => $name)
                            <option value="{{ $code }}" {{ $code == 'DE' ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="co" class="block text-sm font-medium">C/O</label>
                    <input type="text" name="co" id="co" class="w-full border-gray-300 rounded">
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">💾 Speichern</button>
        </div>
    </form>
</div>
@endsection
