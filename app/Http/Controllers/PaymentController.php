<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Formular anzeigen
    |--------------------------------------------------------------------------
    */

    public function create(Invoice $invoice)
    {
        abort_if(
            $invoice->tenant_id !== auth()->user()->tenant_id,
            403
        );

        $accounts = Account::where('tenant_id', auth()->user()->tenant_id)
            ->whereIn('type', ['bank', 'kasse'])
            ->get();

        return view('payments.create', compact(
            'invoice',
            'accounts'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Zahlung speichern
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Invoice $invoice)
    {
        abort_if(
            $invoice->tenant_id !== auth()->user()->tenant_id,
            403
        );

        $request->validate([
            'account_id'   => 'required|exists:accounts,id',
            'amount'       => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'note'         => 'nullable|string|max:255',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Payment speichern
        |--------------------------------------------------------------------------
        */

        $payment = Payment::create([

            'tenant_id'    => auth()->user()->tenant_id,
            'invoice_id'   => $invoice->id,
            'account_id'   => $request->account_id,

            'amount'       => $request->amount,
            'payment_date' => $request->payment_date,
            'note'         => $request->note,

        ]);

/*
|--------------------------------------------------------------------------
| Einnahmekonto Mitgliederbeiträge holen
|--------------------------------------------------------------------------
*/

$incomeAccount = \App\Models\Account::where('tenant_id', auth()->user()->tenant_id)
    ->where('name', 'Mitgliederbeiträge')
    ->first();

if (!$incomeAccount) {
    abort(500, 'Kein Konto "Mitgliederbeiträge" gefunden');
}


/*
|--------------------------------------------------------------------------
| Transaction erzeugen
|--------------------------------------------------------------------------
*/

$transaction = Transaction::create([

    'tenant_id' => auth()->user()->tenant_id,

    // Einnahme -> Bank/Kasse
    'account_from_id' => $incomeAccount->id,
    'account_to_id'   => $request->account_id,

    'amount' => $request->amount,

    'date' => $request->payment_date,

    'description' => 'Zahlung Rechnung ' . $invoice->invoice_number,

]);


/*
|--------------------------------------------------------------------------
| Kontostände neu berechnen
|--------------------------------------------------------------------------
*/

$fromAccount = \App\Models\Account::find($incomeAccount->id);
$toAccount   = \App\Models\Account::find($request->account_id);

if ($fromAccount) {
    $fromAccount->updateBalance();
}

if ($toAccount) {
    $toAccount->updateBalance();
}
        /*
        |--------------------------------------------------------------------------
        | Rechnung bezahlt?
        |--------------------------------------------------------------------------
        */

        $paid = $invoice->payments()->sum('amount');

        if ($paid >= $invoice->getTotal()) {

            $invoice->markAsPaid();

        }


        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Zahlung gebucht');

    }
}