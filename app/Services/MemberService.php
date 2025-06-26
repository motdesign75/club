<?php

namespace App\Services;

use App\Models\Member;
use App\Models\CustomMemberValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberService
{
    /**
     * Neues Mitglied erstellen
     */
    public function create(Request $request): void
    {
        $validated = $request->validated();

        $data = array_merge($validated, [
            'tenant_id' => app('currentTenant')->id,
            'country'   => $validated['country'] ?? 'DE',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        // Nur Felder speichern, die auch im Model fillable sind
        $allowed = [
            'tenant_id', 'gender', 'salutation', 'title', 'first_name', 'last_name',
            'organization', 'birthday', 'member_id', 'entry_date', 'exit_date',
            'termination_date', 'email', 'mobile', 'landline', 'street',
            'address_addition', 'zip', 'city', 'country', 'care_of',
            'membership_id', 'photo'
        ];

        $member = Member::create(array_intersect_key($data, array_flip($allowed)));

        // Benutzerdefinierte Felder speichern
        $this->syncCustomFields($member, $request->input('custom_fields', []));
    }

    /**
     * Bestehendes Mitglied aktualisieren
     */
    public function update(Request $request, Member $member): void
    {
        $validated = $request->validated();

        $data = array_merge($validated, [
            'country' => $validated['country'] ?? $member->country,
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }

            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $allowed = [
            'gender', 'salutation', 'title', 'first_name', 'last_name',
            'organization', 'birthday', 'member_id', 'entry_date', 'exit_date',
            'termination_date', 'email', 'mobile', 'landline', 'street',
            'address_addition', 'zip', 'city', 'country', 'care_of',
            'membership_id', 'photo'
        ];

        $member->update(array_intersect_key($data, array_flip($allowed)));

        // Benutzerdefinierte Felder aktualisieren
        $this->syncCustomFields($member, $request->input('custom_fields', []));
    }

    /**
     * Benutzerdefinierte Felder synchronisieren
     */
    protected function syncCustomFields(Member $member, array $fields): void
    {
        foreach ($fields as $fieldId => $value) {
            CustomMemberValue::updateOrCreate(
                [
                    'member_id' => $member->id,
                    'custom_member_field_id' => $fieldId,
                ],
                [
                    'value' => $value,
                ]
            );
        }
    }
}
