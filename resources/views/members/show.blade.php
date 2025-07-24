@extends('layouts.app')
@include('layouts.navigation')

@section('title', 'Mitglied: ' . $member->first_name . ' ' . $member->last_name)

@section('content')
<div class="max-w-6xl mx-auto text-gray-800 space-y-8">

    {{-- Header mit Profilbild und Name --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            @if($member->photo)
                <img src="{{ asset('storage/' . $member->photo) }}"
                     alt="Profilbild"
                     class="w-20 h-20 object-cover rounded-full border shadow">
            @else
                <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-2xl text-gray-500">
                    👤
                </div>
            @endif
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#2954A3]">
                    {{ $member->salutation }} {{ $member->first_name }} {{ $member->last_name }}
                </h1>
                <p class="text-sm text-gray-500">{{ $member->email }}</p>
            </div>
        </div>

        <a href="{{ route('members.edit', $member) }}"
           class="bg-[#2954A3] hover:bg-[#1E3F7F] text-white px-6 py-2 rounded-xl shadow transition">
            ✏️ Bearbeiten
        </a>
    </div>

    {{-- Sektionen als Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach([
            '🧍 Mitglied' => [
                ['label' => 'Geschlecht', 'value' => $member->gender],
                ['label' => 'Anrede', 'value' => $member->salutation],
                ['label' => 'Titel', 'value' => $member->title],
                ['label' => 'Vorname', 'value' => $member->first_name],
                ['label' => 'Nachname', 'value' => $member->last_name],
                ['label' => 'Organisation', 'value' => $member->organization],
                ['label' => 'Geburtstag', 'value' => $member->birthday ? \Carbon\Carbon::parse($member->birthday)->format('d.m.Y') : '–'],
            ],
            '📝 Mitgliedschaft' => [
                ['label' => 'Mitgliedschaft', 'value' => $member->membership ? $member->membership->name . ' – ' . number_format($member->membership->fee, 2, ',', '.') . ' € / ' . $member->membership->billing_cycle : '–'],
                ['label' => 'Mitgliedsnummer', 'value' => $member->member_id],
                ['label' => 'Eintritt', 'value' => $member->entry_date ? \Carbon\Carbon::parse($member->entry_date)->format('d.m.Y') : '–'],
                ['label' => 'Austritt', 'value' => $member->exit_date ? \Carbon\Carbon::parse($member->exit_date)->format('d.m.Y') : '–'],
                ['label' => 'Kündigungsdatum', 'value' => $member->termination_date ? \Carbon\Carbon::parse($member->termination_date)->format('d.m.Y') : '–'],
            ],
            '📞 Kommunikation' => [
                ['label' => 'Mobil', 'value' => $member->mobile, 'link' => 'tel:' . $member->mobile],
                ['label' => 'Festnetz', 'value' => $member->landline, 'link' => 'tel:' . $member->landline],
                ['label' => 'E-Mail', 'value' => $member->email, 'link' => 'mailto:' . $member->email],
            ],
            '📍 Adresse' => [
                ['label' => 'Straße + Nr.', 'value' => $member->street],
                ['label' => 'Adresszusatz', 'value' => $member->address_addition],
                ['label' => 'PLZ', 'value' => $member->zip],
                ['label' => 'Ort', 'value' => $member->city],
                ['label' => 'Land', 'value' => config('countries.list')[$member->country] ?? $member->country],
                ['label' => 'C/O', 'value' => $member->care_of],
            ]
        ] as $heading => $fields)
            <div class="bg-white rounded-2xl shadow-md p-5 space-y-4 ring-2 ring-gray-100">
                <h2 class="text-lg font-semibold text-[#2954A3]">{{ $heading }}</h2>
                <div class="space-y-2 text-sm">
                    @foreach($fields as $field)
                        <div class="flex justify-between items-center border-b pb-1">
                            <span class="text-gray-600">{{ $field['label'] }}</span>
                            <span class="text-right text-gray-900">
                                @if(!empty($field['link']) && !empty($field['value']))
                                    <a href="{{ $field['link'] }}" class="text-blue-600 underline hover:text-blue-800">{{ $field['value'] }}</a>
                                @else
                                    {{ $field['value'] ?? '–' }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Tags --}}
    @if($member->tags->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-md p-5 ring-2 ring-yellow-100">
            <h2 class="text-lg font-semibold text-yellow-700 mb-2">🏷️ Tags</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($member->tags as $tag)
                    <span class="inline-block text-xs px-2 py-1 rounded font-medium"
                          style="background-color: {{ $tag->color ?? '#E5E7EB' }}; color: #111827;">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Benutzerdefinierte Felder --}}
    @if($customFields->count())
        <div class="bg-white rounded-2xl shadow-md p-5 ring-2 ring-pink-100">
            <h2 class="text-lg font-semibold text-pink-600 mb-2">🧩 Benutzerdefinierte Felder</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <dt class="text-sm text-gray-600">{{ $field->label }}</dt>
                        <dd class="text-base text-gray-900">{{ $value }}</dd>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Zurück-Link --}}
    <div class="pt-4">
        <a href="{{ route('members.index') }}"
           class="text-sm text-gray-600 hover:text-[#2954A3] underline">
            ← Zur Mitgliederübersicht
        </a>
    </div>
</div>
@endsection
