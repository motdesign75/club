@extends('layouts.app')
@include('layouts.navigation')

@section('title', 'Mitglied: ' . $member->first_name . ' ' . $member->last_name)

@section('content')
<div class="max-w-6xl mx-auto space-y-10 text-gray-800" aria-labelledby="mitgliedsdaten">
    <header class="flex justify-between items-center">
        <h1 id="mitgliedsdaten" class="text-3xl font-extrabold text-[#2954A3]">
            👤 Mitglied: {{ $member->salutation }} {{ $member->first_name }} {{ $member->last_name }}
        </h1>
        <a href="{{ route('members.edit', $member) }}"
           class="bg-[#2954A3] text-white px-5 py-2 rounded-2xl shadow hover:bg-[#1E3F7F] transition-all"
           aria-label="Mitgliedsdaten bearbeiten">
            ✏️ Bearbeiten
        </a>
    </header>

    {{-- Profilbild --}}
    @if($member->photo)
        <figure class="flex justify-center">
            <img src="{{ asset('storage/' . $member->photo) }}"
                 alt="Profilfoto von {{ $member->full_name }}"
                 class="w-32 h-32 object-cover rounded-full border shadow">
        </figure>
    @endif

    {{-- Sektionen --}}
    @php
        $sections = [
            '🧍 Mitglied' => [
                ['label' => 'Geschlecht', 'value' => $member->gender],
                ['label' => 'Anrede', 'value' => $member->salutation],
                ['label' => 'Titel', 'value' => $member->title],
                ['label' => 'Vorname', 'value' => $member->first_name],
                ['label' => 'Nachname', 'value' => $member->last_name],
                ['label' => 'Organisation', 'value' => $member->organization],
                ['label' => 'Geburtstag', 'value' => $member->birthday],
            ],
            '📝 Mitgliedschaft' => [
                ['label' => 'Mitgliedschaft', 'value' => $member->membership ? $member->membership->name . ' – ' . number_format($member->membership->fee, 2, ',', '.') . ' € / ' . $member->membership->billing_cycle : '–'],
                ['label' => 'Mitgliedsnummer', 'value' => $member->member_id],
                ['label' => 'Eintritt', 'value' => $member->entry_date],
                ['label' => 'Austritt', 'value' => $member->exit_date],
                ['label' => 'Kündigungsdatum', 'value' => $member->termination_date],
            ],
            '📞 Kommunikation' => [
                ['label' => 'E-Mail', 'value' => $member->email, 'link' => 'mailto:' . $member->email],
                ['label' => 'Mobil', 'value' => $member->mobile, 'link' => 'tel:' . $member->mobile],
                ['label' => 'Festnetz', 'value' => $member->landline, 'link' => 'tel:' . $member->landline],
            ],
            '📍 Adresse' => [
                ['label' => 'Straße + Nr.', 'value' => $member->street],
                ['label' => 'Adresszusatz', 'value' => $member->address_addition],
                ['label' => 'PLZ', 'value' => $member->zip],
                ['label' => 'Ort', 'value' => $member->city],
                ['label' => 'Land', 'value' => config('countries.list')[$member->country] ?? $member->country],
                ['label' => 'C/O', 'value' => $member->care_of],
            ]
        ];
    @endphp

    @foreach($sections as $heading => $fields)
        <section class="bg-white rounded-2xl shadow p-6 ring-4 ring-gray-100" aria-labelledby="section-{{ \Str::slug($heading) }}">
            <h2 id="section-{{ \Str::slug($heading) }}" class="text-xl font-semibold text-[#2954A3] mb-4">{{ $heading }}</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($fields as $field)
                    <div>
                        <dt class="text-sm font-medium text-gray-600">{{ $field['label'] }}</dt>
                        <dd class="text-base text-gray-900">
                            @if(!empty($field['link']) && !empty($field['value']))
                                <a href="{{ $field['link'] }}" class="underline hover:text-[#2954A3]">{{ $field['value'] }}</a>
                            @else
                                {{ $field['value'] ?? '–' }}
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </section>
    @endforeach

    {{-- Benutzerdefinierte Felder --}}
    @if($customFields->count())
        <section class="bg-white rounded-2xl shadow p-6 ring-4 ring-[#FCE7F3]" aria-labelledby="section-benutzerdefiniert">
            <h2 id="section-benutzerdefiniert" class="text-xl font-semibold text-pink-600 mb-4">🧩 Benutzerdefinierte Felder</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($customFields as $field)
                    @php
                        $value = optional($member->customValues->firstWhere('custom_member_field_id', $field->id))->value ?? '–';
                        if ($field->type === 'date' && $value !== '–') {
                            $value = \Carbon\Carbon::parse($value)->format('d.m.Y');
                        }
                        if ($field->type === 'select' && $value !== '–' && $field->options) {
                            $options = explode('|', $field->options);
                            $value = in_array($value, $options) ? $value : '–';
                        }
                    @endphp
                    <div>
                        <dt class="text-sm font-medium text-gray-600">{{ $field->label }}</dt>
                        <dd class="text-base text-gray-900">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    @endif

    {{-- Zurück-Link --}}
    <div class="pt-4">
        <a href="{{ route('members.index') }}"
           class="text-sm text-gray-600 hover:text-[#2954A3] underline"
           aria-label="Zurück zur Mitgliederliste">
            ← Zur Mitgliederübersicht
        </a>
    </div>
</div>
@endsection
