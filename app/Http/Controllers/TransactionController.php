<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // Zeigt alle Buchungen
    public function index()
    {
        $transactions = Transaction::forCurrentTenant()
            ->with(['account_from', 'account_to'])
            ->orderByDesc('date')
            ->get();

        return view('transactions.index', compact('transactions'));
    }

    // Formular für neue Buchung
    public function create()
    {
        $accounts = Account::forCurrentTenant()->orderBy('number')->get();
        return view('transactions.create', compact('accounts'));
    }

    // Speichert eine neue Buchung
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'account_from_id' => ['required', 'exists:accounts,id'],
            'account_to_id' => ['required', 'exists:accounts,id'],
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Buchung erfolgreich gespeichert.');
    }

    // Formular zur Bearbeitung einer Buchung
    public function edit(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $accounts = Account::forCurrentTenant()->orderBy('number')->get();
        return view('transactions.edit', compact('transaction', 'accounts'));
    }

    // Speichert Änderungen an einer Buchung
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'account_from_id' => ['required', 'exists:accounts,id'],
            'account_to_id' => ['required', 'exists:accounts,id'],
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Buchung aktualisiert.');
    }

    // Löscht eine Buchung
    public function destroy(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Buchung gelöscht.');
    }

    // Prüft, ob der Benutzer Zugriff auf die Buchung hat
    protected function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Kein Zugriff auf diese Buchung.');
        }
    }

    /**
     * Einnahmen- & Ausgabenübersicht
     */
    public function summary(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $start = $request->input('start', Carbon::now()->startOfYear()->format('Y-m-d'));
        $end = $request->input('end', Carbon::now()->endOfYear()->format('Y-m-d'));

        $transactions = Transaction::where('tenant_id', $tenantId)
            ->whereBetween('date', [$start, $end])
            ->with(['account_from', 'account_to'])
            ->get();

        // Einnahmen: Kontoart 'einnahme' beim Quellkonto
        $income = $transactions
            ->filter(fn ($t) => $t->account_from && $t->account_from->type === 'einnahme')
            ->sum('amount');

        // Ausgaben: Kontoart 'ausgabe' beim Zielkonto
        $expense = $transactions
            ->filter(fn ($t) => $t->account_to && $t->account_to->type === 'ausgabe')
            ->sum('amount');

        $balance = $income - $expense;

        // Monatliche Gruppierung
        $byMonth = $transactions->groupBy(function ($t) {
            return Carbon::parse($t->date)->format('Y-m');
        })->map(function ($group) {
            $income = $group->filter(fn ($t) => $t->account_from && $t->account_from->type === 'einnahme')->sum('amount');
            $expense = $group->filter(fn ($t) => $t->account_to && $t->account_to->type === 'ausgabe')->sum('amount');
            return [
                'income' => $income,
                'expense' => $expense,
                'saldo' => $income - $expense,
            ];
        });

        $summary = [
            'total_income' => $income,
            'total_expense' => $expense,
            'saldo' => $balance,
            'by_month' => $byMonth,
        ];

        return view('transactions.summary', compact('summary', 'start', 'end'));
    }
}
