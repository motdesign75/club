<x-app-layout>
    <x-slot name="header">CSV-Vorschau & Feldzuordnung</x-slot>

    <form method="POST" action="{{ route('import.mitglieder.confirm') }}">
        @csrf
        <input type="hidden" name="path" value="{{ $path }}">

        <div class="overflow-auto bg-white p-4 rounded shadow mb-6">
            <table class="table-auto w-full text-sm">
                <thead>
                    <tr>
                        @foreach($headers as $i => $header)
                            <th class="border px-2 py-1">
                                <div class="font-bold">{{ $header }}</div>
                                <div>
                                    <select name="mapping[{{ $i }}]" class="mt-1 block w-full text-sm border rounded">
                                        <option value="skip">Ignorieren</option>
                                        <option value="salutation">Anrede</option>
                                        <option value="first_name">Vorname</option>
                                        <option value="last_name">Nachname</option>
                                        <option value="birthday">Geburtstag</option>
                                        <option value="email">E-Mail</option>
                                        <option value="mobile">Mobilfunknummer</option>
                                        <option value="phone">Telefon</option>
                                        <option value="street">Straße</option>
                                        <option value="zip">PLZ</option>
                                        <option value="city">Ort</option>
                                        <!-- weitere Felder nach Bedarf -->
                                    </select>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr>
                            @foreach($row as $value)
                                <td class="border px-2 py-1">{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-primary-button>Import starten</x-primary-button>
    </form>
</x-app-layout>
