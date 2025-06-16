<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::forCurrentTenant()
            ->with(['account_from', 'account_to'])
            ->orderByDesc('date')
            ->get();

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::forCurrentTenant()->orderBy('number')->get();
        return view('transactions.create', compact('accounts'));
    }

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

    public function edit(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $accounts = Account::forCurrentTenant()->orderBy('number')->get();
        return view('transactions.edit', compact('transaction', 'accounts'));
    }

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

    public function destroy(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Buchung gelöscht.');
    }

    protected function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Kein Zugriff auf diese Buchung.');
        }
    }

    public function summary(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $start = $request->input('start', Carbon::now()->startOfYear()->format('Y-m-d'));
        $end = $request->input('end', Carbon::now()->endOfYear()->format('Y-m-d'));

        $transactions = Transaction::where('tenant_id', $tenantId)
            ->whereBetween('date', [$start, $end])
            ->with(['account_from', 'account_to'])
            ->orderBy('date')
            ->get();

        // Einnahmen: wenn das FROM-Konto vom Typ 'einnahme' ist
        $income = $transactions->filter(fn($t) =>
            optional($t->account_from)->type === 'einnahme'
        )->sum('amount');

        // Ausgaben: wenn das TO-Konto vom Typ 'ausgabe' ist
        $expense = $transactions->filter(fn($t) =>
            optional($t->account_to)->type === 'ausgabe'
        )->sum('amount');

        $saldo = $income - $expense;

        // Monatsweise Gruppierung
        $byMonth = $transactions->groupBy(function ($t) {
            return Carbon::parse($t->date)->format('Y-m');
        })->map(function ($items) {
            $income = $items->filter(fn($t) =>
                optional($t->account_from)->type === 'einnahme'
            )->sum('amount');

            $expense = $items->filter(fn($t) =>
                optional($t->account_to)->type === 'ausgabe'
            )->sum('amount');

            return [
                'income' => $income,
                'expense' => $expense,
                'saldo' => $income - $expense,
            ];
        });

        // Aktueller und Vormonat
        $currentMonthKey = Carbon::now()->format('Y-m');
        $previousMonthKey = Carbon::now()->subMonth()->format('Y-m');

        $current = $byMonth->get($currentMonthKey, ['income' => 0, 'expense' => 0, 'saldo' => 0]);
        $previous = $byMonth->get($previousMonthKey, ['income' => 0, 'expense' => 0, 'saldo' => 0]);

        return view('transactions.summary', [
            'summary' => [
                'total_income' => $income,
                'total_expense' => $expense,
                'saldo' => $saldo,
                'by_month' => $byMonth,
                'transactions' => $transactions,
                'current' => $current,
                'previous' => $previous,
            ],
            'start' => $start,
            'end' => $end,
        ]);
    }
}
