<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ggf. später rollenbasiert erweitern
    }

    public function rules(): array
    {
        return [
            'gender'            => 'required|in:weiblich,männlich,divers',
            'salutation'        => 'required|in:Frau,Herr,Liebe,Lieber,Hallo',
            'title'             => 'nullable|string|max:255',
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'organization'      => 'nullable|string|max:255',
            'birthday'          => 'nullable|date',
            'member_id'         => 'nullable|string|max:255',
            'entry_date'        => 'nullable|date',
            'exit_date'         => 'nullable|date',
            'termination_date'  => 'nullable|date',
            'email'             => 'nullable|email|max:255',
            'mobile'            => 'nullable|string|max:255',
            'landline'          => 'nullable|string|max:255',
            'street'            => 'nullable|string|max:255',
            'address_addition'  => 'nullable|string|max:255',
            'zip'               => 'nullable|string|max:20',
            'city'              => 'nullable|string|max:255',
            'country'           => 'nullable|string|max:255',
            'care_of'           => 'nullable|string|max:255',
            'membership_id'     => 'required|exists:memberships,id',
            'photo'             => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'     => 'Der Vorname ist erforderlich.',
            'last_name.required'      => 'Der Nachname ist erforderlich.',
            'gender.required'         => 'Bitte wählen Sie ein Geschlecht.',
            'salutation.required'     => 'Bitte wählen Sie eine Anrede.',
            'membership_id.required'  => 'Bitte wählen Sie eine Mitgliedschaft.',
            'membership_id.exists'    => 'Die gewählte Mitgliedschaft ist ungültig.',
        ];
    }
}
