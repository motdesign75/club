<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Zeigt das Registrierungsformular an.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Speichert einen neuen Benutzer und erstellt gleichzeitig den Verein (Tenant).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'verein'   => ['required', 'string', 'max:255'],
        ]);

        $slug = Str::slug($request->input('verein'));

        // Slug doppelt?
        if (Tenant::where('slug', $slug)->exists()) {
            return back()
                ->withErrors(['verein' => 'Ein Verein mit diesem Namen wurde bereits registriert.'])
                ->withInput();
        }

        // E-Mail doppelt?
        if (Tenant::where('email', $request->input('email'))->exists()) {
            return back()
                ->withErrors(['email' => 'Diese E-Mail-Adresse ist bereits einem anderen Verein zugeordnet.'])
                ->withInput();
        }

        // Schritt 1: Verein (Mandant) anlegen
        $tenant = Tenant::create([
            'name'  => $request->input('verein'),
            'slug'  => $slug,
            'email' => $request->input('email'),
        ]);

        

        // Schritt 2: Benutzer dem Verein zuordnen
        $user = User::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => Hash::make($request->input('password')),
            'tenant_id' => $tenant->id,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
