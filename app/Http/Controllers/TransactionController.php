<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{

    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $year = $request->input('year');
        $month = $request->input('month');

        $transactions = Transaction::forCurrentTenant()
            ->with(['account_from', 'account_to'])
            ->orderByDesc('date');

        if ($filter === 'income') {
            $transactions->whereHas('account_from', fn($q) => $q->where('type', 'einnahme'));
        }

        if ($filter === 'expense') {
            $transactions->whereHas('account_to', fn($q) => $q->where('type', 'ausgabe'));
        }

        if ($filter === 'storno') {
            $transactions->where('description', 'like', 'Storno:%');
        }

        if ($year) {
            $transactions->whereYear('date', $year);
        }

        if ($month) {
            $transactions->whereMonth('date', $month);
        }

        $transactions = $transactions->get()->map(function ($transaction) {

            $exists = $transaction->receipt_file
                ? Storage::disk('public')->exists($transaction->receipt_file)
                : false;

            $transaction->receipt_exists = $exists;
            $transaction->receipt_url = $exists
                ? asset('storage/' . $transaction->receipt_file)
                : null;

            return $transaction;
        });

        return view('transactions.index', compact('transactions', 'filter', 'year', 'month'));
    }

    /**
     * 🔥 NEU: Edit
     */
    public function edit(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $accounts = Account::forCurrentTenant()
            ->orderBy('number')
            ->get();

        return view('transactions.edit', compact('transaction', 'accounts'));
    }

    /**
     * 🔥 NEU: Update
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'account_from_id' => ['required', 'exists:accounts,id'],
            'account_to_id' => ['required', 'exists:accounts,id'],
            'tax_area' => ['required', 'in:ideell,zweckbetrieb,wirtschaftlich'],
            'receipt_file' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
        ]);

        $transaction->update([
            'date' => $validated['date'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'account_from_id' => $validated['account_from_id'],
            'account_to_id' => $validated['account_to_id'],
            'tax_area' => $validated['tax_area'],
        ]);

        // 🔥 Beleg ersetzen
        if ($request->hasFile('receipt_file')) {

            // alten Beleg löschen (wenn vorhanden)
            if ($transaction->receipt_file && Storage::disk('public')->exists($transaction->receipt_file)) {
                Storage::disk('public')->delete($transaction->receipt_file);
            }

            $transaction->update([
                'receipt_file' => $request->file('receipt_file')->store(
                    'receipts/' . auth()->user()->tenant_id,
                    'public'
                )
            ]);
        }

        $transaction->account_from?->updateBalance();
        $transaction->account_to?->updateBalance();

        return redirect()->route('transactions.index')
            ->with('success', 'Buchung erfolgreich aktualisiert.');
    }

    private function getJournalData(Request $request)
    {
        $filter = $request->input('filter');
        $year = $request->input('year');
        $month = $request->input('month');

        $transactions = Transaction::forCurrentTenant()
            ->with(['account_from', 'account_to'])
            ->orderBy('date');

        if ($filter === 'income') {
            $transactions->whereHas('account_from', fn($q) => $q->where('type', 'einnahme'));
        }

        if ($filter === 'expense') {
            $transactions->whereHas('account_to', fn($q) => $q->where('type', 'ausgabe'));
        }

        if ($filter === 'storno') {
            $transactions->where('description', 'like', 'Storno:%');
        }

        if ($year) {
            $transactions->whereYear('date', $year);
        }

        if ($month) {
            $transactions->whereMonth('date', $month);
        }

        $transactions = $transactions->get();

        $totalIncome = $transactions
            ->filter(fn($t) => in_array(optional($t->account_to)->type, ['bank','kasse']))
            ->sum('amount');

        $totalExpense = $transactions
            ->filter(fn($t) => in_array(optional($t->account_from)->type, ['bank','kasse']))
            ->sum('amount');

        $saldo = $totalIncome - $totalExpense;

        $tenant = auth()->user()->tenant;

        return compact(
            'transactions',
            'filter',
            'year',
            'month',
            'tenant',
            'totalIncome',
            'totalExpense',
            'saldo'
        );
    }

    public function journal(Request $request)
    {
        $data = $this->getJournalData($request);
        return view('transactions.journal', $data);
    }

    public function eur(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $start = $request->input('start', Carbon::now()->startOfYear()->format('Y-m-d'));
        $end = $request->input('end', Carbon::now()->endOfYear()->format('Y-m-d'));

        $transactions = Transaction::where('tenant_id', $tenantId)
            ->whereBetween('date', [$start, $end])
            ->with(['account_from', 'account_to'])
            ->get();

        $totalIncome = $transactions
            ->filter(fn($t) => optional($t->account_from)->type === 'einnahme')
            ->sum('amount');

        $totalExpense = $transactions
            ->filter(fn($t) => optional($t->account_to)->type === 'ausgabe')
            ->sum('amount');

        $result = $transactions->groupBy('tax_area')->map(function ($items) {

            $income = $items
                ->filter(fn($t) => optional($t->account_from)->type === 'einnahme')
                ->sum('amount');

            $expense = $items
                ->filter(fn($t) => optional($t->account_to)->type === 'ausgabe')
                ->sum('amount');

            return [
                'income' => $income,
                'expense' => $expense,
                'saldo' => $income - $expense,
            ];
        });

        return view('transactions.eur', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'saldo' => $totalIncome - $totalExpense,
            'result' => $result,
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function summary(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $start = $request->input('start', Carbon::now()->startOfYear()->format('Y-m-d'));
        $end = $request->input('end', Carbon::now()->endOfYear()->format('Y-m-d'));

        $transactions = Transaction::where('tenant_id', $tenantId)
            ->whereBetween('date', [$start, $end])
            ->with(['account_from', 'account_to'])
            ->get();

        $totalIncome = $transactions
            ->filter(fn($t) => optional($t->account_from)->type === 'einnahme')
            ->sum('amount');

        $totalExpense = $transactions
            ->filter(fn($t) => optional($t->account_to)->type === 'ausgabe')
            ->sum('amount');

        return view('transactions.summary', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'saldo' => $totalIncome - $totalExpense,
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function journalPdf(Request $request)
    {
        $data = $this->getJournalData($request);

        $pdf = Pdf::loadView('transactions.journal', $data)
            ->setPaper('a4', 'landscape');

        $year = $data['year'] ?? 'alle';
        $month = $data['month'] ?? 'alle';

        return $pdf->download("Buchungsjournal_{$year}_{$month}.pdf");
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
            'tax_area' => ['required', 'in:ideell,zweckbetrieb,wirtschaftlich'],
            'receipt_file' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
        ]);

        $latest = Transaction::orderBy('id', 'desc')->first();
        $nextNumber = $latest ? $latest->id + 1 : 1;
        $receiptNumber = 'TRX-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $transaction = new Transaction();
        $transaction->tenant_id = auth()->user()->tenant_id;
        $transaction->date = $validated['date'];
        $transaction->description = $validated['description'];
        $transaction->amount = $validated['amount'];
        $transaction->account_from_id = $validated['account_from_id'];
        $transaction->account_to_id = $validated['account_to_id'];
        $transaction->tax_area = $validated['tax_area'];
        $transaction->receipt_number = $receiptNumber;

        if ($request->hasFile('receipt_file')) {
            $transaction->receipt_file = $request->file('receipt_file')->store(
                'receipts/' . auth()->user()->tenant_id,
                'public'
            );
        }

        $transaction->save();

        $transaction->account_from?->updateBalance();
        $transaction->account_to?->updateBalance();

        return redirect()->route('transactions.index')
            ->with('success', 'Buchung erfolgreich gespeichert.');
    }

    protected function authorizeTransaction(Transaction $transaction)
    {
        if (!$transaction || $transaction->tenant_id != auth()->user()->tenant_id) {
            abort(403, 'Kein Zugriff auf diese Buchung.');
        }
    }
}