<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = app('currentTenant')->id;

        $query = Member::where('tenant_id', $tenantId);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('member_id', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'last_name');
        $sortDirection = $request->get('direction', 'asc');
        $allowedFields = ['first_name', 'last_name', 'email', 'member_id', 'entry_date'];
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'last_name';
        }

        $members = $query->orderBy($sortField, $sortDirection)
                         ->paginate(25)
                         ->appends($request->query());

        return view('members.index', compact('members', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        $memberships = Membership::where('tenant_id', app('currentTenant')->id)->get();
        return view('members.create', compact('memberships'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gender'            => 'nullable|string|in:weiblich,männlich,divers',
            'salutation'        => 'nullable|string|in:Frau,Herr,Liebe,Lieber,Hallo',
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
            'membership_id'     => 'nullable|exists:memberships,id',
            'photo'             => 'nullable|image|max:2048',
        ]);

        $validated['tenant_id'] = app('currentTenant')->id;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Mitglied hinzugefügt.');
    }

    public function show(Member $member)
    {
        $this->authorizeMember($member);
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $this->authorizeMember($member);
        $memberships = Membership::where('tenant_id', app('currentTenant')->id)->get();
        return view('members.edit', compact('member', 'memberships'));
    }

    public function update(Request $request, Member $member)
    {
        $this->authorizeMember($member);

        $validated = $request->validate([
            'gender'            => 'nullable|string|in:weiblich,männlich,divers',
            'salutation'        => 'nullable|string|in:Frau,Herr,Liebe,Lieber,Hallo',
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
            'membership_id'     => 'nullable|exists:memberships,id',
            'photo'             => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }

            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'Mitglied aktualisiert.');
    }

    public function destroy(Member $member)
    {
        $this->authorizeMember($member);

        if ($member->photo && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();
        return redirect()->route('members.index')->with('success', 'Mitglied gelöscht.');
    }

    /**
     * ➕ DSGVO-Datenauskunft als PDF nach Art. 15 DSGVO
     */
    public function exportDatenauskunft(Member $member)
    {
        $this->authorizeMember($member);

        $pdf = Pdf::loadView('members.pdf.datenauskunft', [
            'member' => $member
        ]);

        return $pdf->download("Datenauskunft_{$member->last_name}_{$member->id}.pdf");
    }

    /**
     * ⚠️ Mandantenschutz: Zugriff nur auf eigene Vereinsmitglieder
     */
    private function authorizeMember(Member $member)
    {
        if ($member->tenant_id !== app('currentTenant')->id) {
            abort(403, 'Unberechtigter Zugriff.');
        }
    }
}
