@extends('layouts.sidebar')

@section('title', 'Mitglied bearbeiten')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">✏️ Mitglied bearbeiten</h1>

        <form action="{{ route('members.update', $member) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6 space-y-6">
            @csrf
            @method('PATCH')

            {{-- Block: Mitglied --}}
            <h2 class="text-lg font-semibold text-gray-700">🧍 Mitglied</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block">Geschlecht</label>
                    <select name="gender" id="gender" class="w-full">
                        <option value="">Bitte wählen</option>
                        <option value="weiblich" {{ $member->gender === 'weiblich' ? 'selected' : '' }}>weiblich</option>
                        <option value="männlich" {{ $member->gender === 'männlich' ? 'selected' : '' }}>männlich</option>
                        <option value="divers" {{ $member->gender === 'divers' ? 'selected' : '' }}>divers</option>
                    </select>
                </div>

                <div>
                    <label for="salutation" class="block">Anrede</label>
                    <select name="salutation" id="salutation" class="w-full">
                        @foreach (['Frau', 'Herr', 'Liebe', 'Lieber', 'Hallo'] as $option)
                            <option value="{{ $option }}" {{ $member->salutation === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="title" class="block">Titel</label>
                    <input type="text" name="title" id="title" class="w-full" value="{{ old('title', $member->title) }}">
                </div>

                <div>
                    <label for="first_name" class="block">Vorname</label>
                    <input type="text" name="first_name" id="first_name" class="w-full" value="{{ old('first_name', $member->first_name) }}" required>
                </div>

                <div>
                    <label for="last_name" class="block">Nachname</label>
                    <input type="text" name="last_name" id="last_name" class="w-full" value="{{ old('last_name', $member->last_name) }}" required>
                </div>

                <div>
                    <label for="organization" class="block">Firma / Organisation</label>
                    <input type="text" name="organization" id="organization" class="w-full" value="{{ old('organization', $member->organization) }}">
                </div>

                <div>
                    <label for="birthday" class="block">Geburtstag</label>
                    <input type="date" name="birthday" id="birthday" class="w-full" value="{{ old('birthday', $member->birthday) }}">
                </div>

                <div>
                    <label for="photo" class="block">Profilfoto</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="w-full file:border file:bg-gray-100 file:rounded file:px-3 file:py-1">
                    @if ($member->photo)
                        <div class="mt-2 flex items-center gap-4">
                            <img src="{{ asset('storage/' . $member->photo) }}"
                                 alt="Profilfoto von {{ $member->first_name }}"
                                 class="w-24 h-24 object-cover rounded-full border border-gray-300 shadow"
                                 loading="lazy">
                            <span class="text-sm text-gray-600 italic">Aktuelles Foto</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Block: Mitgliedschaft --}}
            <h2 class="text-lg font-semibold text-gray-700">📝 Mitgliedschaft</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="membership_id" class="block">Mitgliedschaft</label>
                    <select name="membership_id" id="membership_id" class="w-full">
                        <option value="">– bitte wählen –</option>
                        @foreach($memberships as $membership)
                            <option value="{{ $membership->id }}"
                                {{ $member->membership_id == $membership->id ? 'selected' : '' }}>
                                {{ $membership->name }} – {{ number_format($membership->fee, 2, ',', '.') }} € / {{ $membership->billing_cycle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="member_id" class="block">Mitgliedsnummer</label>
                    <input type="text" name="member_id" id="member_id" class="w-full" value="{{ old('member_id', $member->member_id) }}">
                </div>

                <div>
                    <label for="entry_date" class="block">Eintritt</label>
                    <input type="date" name="entry_date" id="entry_date" class="w-full" value="{{ old('entry_date', $member->entry_date) }}">
                </div>

                <div>
                    <label for="exit_date" class="block">Austritt</label>
                    <input type="date" name="exit_date" id="exit_date" class="w-full" value="{{ old('exit_date', $member->exit_date) }}">
                </div>

                <div>
                    <label for="termination_date" class="block">Kündigungsdatum</label>
                    <input type="date" name="termination_date" id="termination_date" class="w-full" value="{{ old('termination_date', $member->termination_date) }}">
                </div>
            </div>

            {{-- Block: Kommunikation --}}
            <h2 class="text-lg font-semibold text-gray-700">📞 Kommunikation</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block">E-Mail</label>
                    <input type="email" name="email" id="email" class="w-full" value="{{ old('email', $member->email) }}">
                </div>
                <div>
                    <label for="mobile" class="block">Mobilfunknummer</label>
                    <input type="text" name="mobile" id="mobile" class="w-full" value="{{ old('mobile', $member->mobile) }}">
                </div>
                <div>
                    <label for="landline" class="block">Festnetznummer</label>
                    <input type="text" name="landline" id="landline" class="w-full" value="{{ old('landline', $member->landline) }}">
                </div>
            </div>

            {{-- Block: Adresse --}}
            <h2 class="text-lg font-semibold text-gray-700">📍 Adresse</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="street" class="block">Straße + Nr.</label>
                    <input type="text" name="street" id="street" class="w-full" value="{{ old('street', $member->street) }}">
                </div>
                <div>
                    <label for="address_addition" class="block">Adresszusatz</label>
                    <input type="text" name="address_addition" id="address_addition" class="w-full" value="{{ old('address_addition', $member->address_addition) }}">
                </div>
                <div>
                    <label for="zip" class="block">PLZ</label>
                    <input type="text" name="zip" id="zip" class="w-full" value="{{ old('zip', $member->zip) }}">
                </div>
                <div>
                    <label for="city" class="block">Ort</label>
                    <input type="text" name="city" id="city" class="w-full" value="{{ old('city', $member->city) }}">
                </div>
                <div>
                    <label for="country" class="block">Land</label>
                    <select name="country" id="country" class="w-full">
                        @foreach (config('countries.list') as $code => $name)
                            <option value="{{ $code }}" {{ $member->country === $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="care_of" class="block">C/O</label>
                    <input type="text" name="care_of" id="care_of" class="w-full" value="{{ old('care_of', $member->care_of) }}">
                </div>
            </div>

            <div class="text-right pt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow">
                    💾 Speichern
                </button>
            </div>
        </form>
    </div>
@endsection
