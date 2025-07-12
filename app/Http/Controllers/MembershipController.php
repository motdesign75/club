<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::where('tenant_id', auth()->user()->tenant_id)->get();

        return view('memberships.index', compact('memberships'));
    }

    public function create()
    {
        return view('memberships.create');
    }

    public function store(Request $request)
    {
        // Betrag mit Komma als Dezimalzeichen → Punkt ersetzen
        $request->merge([
            'amount' => str_replace(',', '.', $request->input('amount'))
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'interval' => 'required|in:monatlich,vierteljährlich,halbjährlich,jährlich',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        Log::debug('Membership Store Request Input:', $request->all());
        Log::debug('Membership Validated Data:', $validated);

        Membership::create($validated);

        return redirect()->route('memberships.index')->with('success', 'Mitgliedschaft erstellt.');
    }

    public function edit(Membership $membership)
    {
        $this->authorizeTenant($membership);

        return view('memberships.edit', compact('membership'));
    }

    public function update(Request $request, Membership $membership)
    {
        $this->authorizeTenant($membership);

        $request->merge([
            'amount' => str_replace(',', '.', $request->input('amount'))
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'interval' => 'required|in:monatlich,vierteljährlich,halbjährlich,jährlich',
        ]);

        Log::debug('Membership Update Request Input:', $request->all());
        Log::debug('Membership Validated Data (Update):', $validated);

        $membership->update($validated);

        return redirect()->route('memberships.index')->with('success', 'Mitgliedschaft aktualisiert.');
    }

    public function destroy(Membership $membership)
    {
        $this->authorizeTenant($membership);

        $membership->delete();

        return redirect()->route('memberships.index')->with('success', 'Mitgliedschaft gelöscht.');
    }

    /**
     * Schutz vor Zugriff auf fremde Daten.
     */
    private function authorizeTenant(Membership $membership)
    {
        if ($membership->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unberechtigter Zugriff.');
        }
    }
}
