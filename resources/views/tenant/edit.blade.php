@extends('layouts.app')

@section('title', 'Vereinsdaten bearbeiten')

@section('content')
<div class="max-w-5xl mx-auto space-y-10 text-gray-800 py-10">

    <h1 class="text-3xl font-extrabold text-[#2954A3]">
        ✏️ Vereinsdaten bearbeiten
    </h1>

    <form method="POST"
          action="{{ route('tenant.update') }}"
          enctype="multipart/form-data"
          class="bg-white shadow-xl ring-1 ring-gray-200 rounded-2xl p-8 space-y-10"
          aria-labelledby="form-vereinsdaten">
        @csrf
        @method('PATCH')

        {{-- Logo --}}
        <section>
            <h2 id="form-vereinsdaten" class="text-xl font-semibold text-gray-700 mb-4">📛 Vereinslogo</h2>
            @if($tenant->logo)
                <div class="mb-4">
                    <img src="{{ Storage::url($tenant->logo) }}" alt="Vereinslogo" class="h-24 rounded shadow inline-block">
                </div>
            @endif
            <input type="file" name="logo" accept="image/*"
                   class="file:rounded file:border file:bg-indigo-50 file:text-indigo-700 file:px-4 file:py-2 w-full border border-gray-300 rounded-lg shadow-sm">
        </section>

        {{-- Stammdaten --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">🏢 Stammdaten</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-ui.input name="name" label="Name *" :value="old('name', $tenant->name)" required />
                <x-ui.input name="slug" label="Slug" :value="old('slug', $tenant->slug)" help="Eindeutiger Bezeichner (z. B. für URLs)" />
                <x-ui.input name="email" label="E-Mail *" type="email" :value="old('email', $tenant->email)" required />
                <x-ui.input name="phone" label="Telefon" :value="old('phone', $tenant->phone)" />
                <x-ui.input name="register_number" label="Registernummer" :value="old('register_number', $tenant->register_number)" />
                <x-ui.input name="chairman_name" label="Vorsitzender / Vorsitzende" :value="old('chairman_name', $tenant->chairman_name)" />
            </div>
        </section>

        {{-- Adresse --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">📍 Adresse</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-ui.input name="address" label="Straße / Adresse" :value="old('address', $tenant->address)" />
                <x-ui.input name="zip" label="PLZ" :value="old('zip', $tenant->zip)" />
                <x-ui.input name="city" label="Ort" :value="old('city', $tenant->city)" />
            </div>
        </section>

        {{-- Bankdaten --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">🏦 Bankverbindung</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-ui.input name="iban" label="IBAN" :value="old('iban', $tenant->iban)" />
                <x-ui.input name="bic" label="BIC" :value="old('bic', $tenant->bic)" />
                <x-ui.input name="bank_name" label="Bankname" :value="old('bank_name', $tenant->bank_name)" />
            </div>
        </section>

        {{-- Briefbogen Upload --}}
        <section>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">📄 Briefbogen (PDF oder Bild)</h2>
            @if($tenant->pdf_template)
                @php
                    $ext = strtolower(pathinfo($tenant->pdf_template, PATHINFO_EXTENSION));
                @endphp
                <div class="mb-4">
                    <label class="text-sm text-gray-600 block mb-1">Aktuell:</label>
                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                        <img src="{{ Storage::url($tenant->pdf_template) }}" alt="Briefbogen" class="max-h-40 object-contain border rounded shadow">
                    @else
                        <a href="{{ Storage::url($tenant->pdf_template) }}" target="_blank" class="text-[#2954A3] hover:underline">
                            {{ basename($tenant->pdf_template) }} anzeigen
                        </a>
                    @endif
                </div>
            @endif
            <input type="file" name="pdf_template" accept="application/pdf,image/jpeg,image/png"
                   class="file:rounded file:border file:bg-pink-50 file:text-pink-700 file:px-4 file:py-2 w-full border border-gray-300 rounded-lg shadow-sm mt-2">
            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="use_letterhead" value="1"
                           {{ old('use_letterhead', $tenant->use_letterhead) ? 'checked' : '' }}
                           class="rounded border-gray-300">
                    <span class="ml-2 text-sm text-gray-700">Briefbogen in PDFs als Hintergrund verwenden</span>
                </label>
            </div>
        </section>

        {{-- Speichern --}}
        <div class="text-right pt-4">
            <button type="submit"
                    class="bg-[#2954A3] hover:bg-[#1E3F7F] text-white font-semibold px-8 py-3 rounded-xl shadow-md transition duration-200">
                💾 Speichern
            </button>
        </div>
    </form>
</div>
@endsection
