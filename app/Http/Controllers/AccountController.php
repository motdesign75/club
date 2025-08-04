<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Zeigt die Kontenübersicht mit Tabs (bank/chart/inaktiv).
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'bank'); // Standard: bank

        // Für die View alle drei Gruppen laden
        $balanceAccounts = Account::forCurrentTenant()
            ->where('active', true)
            ->whereIn('type', ['bank', 'kasse'])
            ->orderBy('number')
            ->get();

        $chartAccounts = Account::forCurrentTenant()
            ->where('active', true)
            ->whereIn('type', ['einnahme', 'ausgabe'])
            ->orderBy('number')
            ->get();

        $inactiveAccounts = Account::forCurrentTenant()
            ->where('active', false)
            ->orderBy('number')
            ->get();

        return view('accounts.index', compact('balanceAccounts', 'chartAccounts', 'inactiveAccounts', 'tab'));
    }

    /**
     * Zeigt das Formular zum Erstellen eines neuen Kontos.
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Speichert ein neues Konto.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number'         => ['nullable', 'string', 'max:20'],
            'name'           => ['required', 'string', 'max:255'],
            'type'           => ['required', 'in:bank,kasse,einnahme,ausgabe'],
            'iban'           => ['nullable', 'string', 'max:34'],
            'bic'            => ['nullable', 'string', 'max:11'],
            'description'    => ['nullable', 'string'],
            'balance_start'  => ['nullable', 'numeric'],
            'balance_date'   => ['nullable', 'date'],
            'tax_area'       => ['nullable', 'in:ideell,zweckbetrieb,wirtschaftlich'],
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['online'] = $request->has('online') ? 1 : 0;
        $validated['active'] = $request->has('active') ? 1 : 0;

        Account::create($validated);

        return redirect()->route('accounts.index')->with('success', 'Konto erfolgreich erstellt.');
    }

    /**
     * Zeigt das Bearbeitungsformular für ein Konto.
     */
    public function edit(Account $account)
    {
        $this->authorizeAccount($account);

        return view('accounts.edit', compact('account'));
    }

    /**
     * Aktualisiert ein bestehendes Konto.
     */
    public function update(Request $request, Account $account)
    {
        $this->authorizeAccount($account);

        $validated = $request->validate([
            'number'         => ['nullable', 'string', 'max:20'],
            'name'           => ['required', 'string', 'max:255'],
            'type'           => ['required', 'in:bank,kasse,einnahme,ausgabe'],
            'iban'           => ['nullable', 'string', 'max:34'],
            'bic'            => ['nullable', 'string', 'max:11'],
            'description'    => ['nullable', 'string'],
            'balance_start'  => ['nullable', 'numeric'],
            'balance_date'   => ['nullable', 'date'],
            'tax_area'       => ['nullable', 'in:ideell,zweckbetrieb,wirtschaftlich'],
        ]);

        $validated['online'] = $request->has('online') ? 1 : 0;
        $validated['active'] = $request->has('active') ? 1 : 0;

        $account->update($validated);

        return redirect()->route('accounts.index')->with('success', 'Konto erfolgreich aktualisiert.');
    }

    /**
     * Löscht ein Konto.
     */
    public function destroy(Account $account)
    {
        $this->authorizeAccount($account);

        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Konto gelöscht.');
    }

    /**
     * Prüft, ob der aktuelle Benutzer auf das Konto zugreifen darf.
     */
    protected function authorizeAccount(Account $account)
    {
        if ((string) $account->tenant_id !== (string) auth()->user()->tenant_id) {
            abort(403, 'Kein Zugriff auf dieses Konto.');
        }
    }
}
