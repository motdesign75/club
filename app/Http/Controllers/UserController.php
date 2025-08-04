<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Zeigt alle Benutzer eines Tenants – inkl. inaktiver, falls vorhanden.
     */
    public function index()
    {
        $users = User::where('tenant_id', auth()->user()->tenant_id)
                     ->orderByDesc('created_at')
                     ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Zeigt das Formular zum Erstellen eines Benutzers.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Speichert einen neuen Benutzer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role'     => ['required', 'string'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['tenant_id'] = auth()->user()->tenant_id;

        // Optional: Standardmäßig als aktiv markieren
        // $validated['active'] = true;

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Benutzer erfolgreich erstellt.');
    }

    /**
     * Löscht einen Benutzer (nur im eigenen Tenant erlaubt).
     */
    public function destroy(User $user)
    {
        // Sicherheit: nicht eigenen Account löschen
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Du kannst deinen eigenen Account nicht löschen.');
        }

        // Sicherheit: nur im eigenen Tenant
        if ((string) $user->tenant_id !== (string) auth()->user()->tenant_id) {
            abort(403, 'Kein Zugriff erlaubt.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Benutzer gelöscht.');
    }
}
