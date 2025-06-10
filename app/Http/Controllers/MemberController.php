<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

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

        $members = $query->get();

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gender'            => 'nullable|string|in:weiblich,männlich,divers',
            'salutation'        => 'nullable|string|in:Frau,Herr,Liebe,Lieber,Hallo',
            'title'             => 'nullable|string|max:255',
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'company'           => 'nullable|string|max:255',
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
        ]);

        $validated['tenant_id'] = app('currentTenant')->id;

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

        return view('members.edit', compact('member'));
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
            'company'           => 'nullable|string|max:255',
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
        ]);

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'Mitglied aktualisiert.');
    }

    public function destroy(Member $member)
    {
        $this->authorizeMember($member);

        $member->delete();

        return redirect()->route('members.index')->with('success', 'Mitglied gelöscht.');
    }

    private function authorizeMember(Member $member)
    {
        if ($member->tenant_id !== app('currentTenant')->id) {
            abort(403, 'Unberechtigter Zugriff.');
        }
    }
}
