<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TenantController extends Controller
{
    public function show(): View
    {
        $tenant = Auth::user()->tenant;
        return view('tenant.show', compact('tenant'));
    }

    public function edit(): View
    {
        $tenant = Auth::user()->tenant;
        return view('tenant.edit', compact('tenant'));
    }

    public function update(Request $request): RedirectResponse
    {
        $tenant = Auth::user()->tenant;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tenants,slug,' . $tenant->id,
            'email' => 'required|email',
            'address' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'register_number' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',

            // Neue Felder
            'iban' => 'nullable|string|max:255',
            'bic' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'chairman_name' => 'nullable|string|max:255',
            'pdf_template' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'use_letterhead' => 'nullable|boolean',
        ]);

        // Logo speichern
        if ($request->hasFile('logo')) {
            if ($tenant->logo) {
                Storage::disk('public')->delete($tenant->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // PDF-Briefbogen speichern
        if ($request->hasFile('pdf_template')) {
            if ($tenant->pdf_template) {
                Storage::disk('public')->delete($tenant->pdf_template);
            }
            $validated['pdf_template'] = $request->file('pdf_template')->store('briefbogen', 'public');
        }

        // Checkbox-Wert setzen
        $validated['use_letterhead'] = $request->has('use_letterhead');

        // Update durchführen
        $tenant->update($validated);

        // Prüfen, ob bereits ein Einladungscode existiert
        if (!$tenant->invitationCode) {
            InvitationCode::create([
                'tenant_id' => $tenant->id,
                'code' => strtoupper(Str::uuid()),
            ]);
        }

        return redirect()->route('tenant.show')->with('success', 'Vereinsdaten wurden aktualisiert.');
    }
}
