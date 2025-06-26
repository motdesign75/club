<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Member;
use Illuminate\Http\Request;
use PDF; // ← Korrekte Verwendung für niklasravnsborg/laravel-pdf

class InvoiceController extends Controller
{
    // Liste der Rechnungen
    public function index()
    {
        $invoices = Invoice::where('tenant_id', auth()->user()->tenant_id)
            ->with('member')
            ->latest()
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    // Formular anzeigen
    public function create()
    {
        $members = Member::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('last_name')
            ->get();

        return view('invoices.create', compact('members'));
    }

    // Rechnung speichern und PDF direkt anzeigen
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'invoice_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $invoiceNumber = Invoice::generateInvoiceNumber();

        $invoice = Invoice::create([
            'tenant_id' => auth()->user()->tenant_id,
            'member_id' => $request->member_id,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $request->invoice_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'entwurf',
        ]);

        $tenant = auth()->user()->tenant;
        $member = $invoice->member;

        $pdf = PDF::loadView('invoices.pdf', compact('invoice', 'tenant', 'member'));

        return $pdf->stream('Rechnung_' . $invoice->invoice_number . '.pdf');
    }

    // Einzelansicht der Rechnung anzeigen
    public function show(Invoice $invoice)
    {
        $this->authorizeAccess($invoice);

        return view('invoices.show', compact('invoice'));
    }

    // PDF-Vorschau manuell abrufen
    public function pdf(Invoice $invoice)
    {
        $this->authorizeAccess($invoice);

        $member = $invoice->member;
        $tenant = auth()->user()->tenant;

        $pdf = PDF::loadView('invoices.pdf', compact('invoice', 'member', 'tenant'));

        return $pdf->stream('Rechnung_' . $invoice->invoice_number . '.pdf');
    }

    // Zugriffsschutz auf eigene Rechnungen
    private function authorizeAccess(Invoice $invoice)
    {
        abort_if($invoice->tenant_id !== auth()->user()->tenant_id, 403);
    }
}
