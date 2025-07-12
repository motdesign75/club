<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Membership;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use Illuminate\Support\Facades\Storage;

class MemberService
{
    public function create(StoreMemberRequest $request): void
    {
        $data = $request->validated();

        $data['tenant_id'] = auth()->user()->tenant_id;

        // Foto speichern, falls vorhanden
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        // Mitgliedschaft abrufen und Betrag/Intervall übernehmen
        if (!empty($data['membership_id'])) {
            $membership = Membership::where('tenant_id', $data['tenant_id'])
                ->where('id', $data['membership_id'])
                ->first();

            if ($membership) {
                $data['membership_amount'] = $membership->fee;
                $data['membership_interval'] = $membership->billing_cycle;
            }
        }

        Member::create($data);
    }

    public function update(UpdateMemberRequest $request, Member $member): void
    {
        $data = $request->validated();

        // Mandant prüfen
        if ($member->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Foto aktualisieren
        if ($request->hasFile('photo')) {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }

            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        // Mitgliedschaft prüfen
        if (!empty($data['membership_id'])) {
            $membership = Membership::where('tenant_id', $member->tenant_id)
                ->where('id', $data['membership_id'])
                ->first();

            if ($membership) {
                $data['membership_amount'] = $membership->fee;
                $data['membership_interval'] = $membership->billing_cycle;
            }
        }

        $member->update($data);
    }
}
