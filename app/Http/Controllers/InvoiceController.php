<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Member;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    // Liste aller Rechnungen
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

    // Rechnung speichern und PDF generieren
    public function store(Request $request)
    {
        $request->validate([
            'member_id'             => 'required|exists:members,id',
            'invoice_date'          => 'required|date',
            'items'                 => 'required|array|min:1',
            'items.*.description'   => 'required|string|max:255',
            'items.*.quantity'      => 'required|numeric|min:0',
            'items.*.unit'          => 'nullable|string|max:50',
            'items.*.unit_price'    => 'required|numeric|min:0',
            'discount'              => 'nullable|numeric|min:0|max:100',
            'tax_rate'              => 'nullable|numeric|min:0|max:100',
        ]);

        $invoiceNumber = Invoice::generateInvoiceNumber();

        $invoice = Invoice::create([
            'tenant_id'      => auth()->user()->tenant_id,
            'member_id'      => $request->member_id,
            'invoice_number' => $invoiceNumber,
            'invoice_date'   => $request->invoice_date,
            'discount'       => $request->discount ?? 0,
            'tax_rate'       => $request->tax_rate ?? 0,
            'status'         => 'entwurf',
        ]);

        // Positionen speichern
        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit'        => $item['unit'] ?? 'Stück',
                'unit_price'  => $item['unit_price'],
            ]);
        }

        // PDF erzeugen
        $invoice->load(['member', 'items']);
        $tenant = auth()->user()->tenant;

        $pdf = PDF::loadView('invoices.pdf', [
            'invoice' => $invoice,
            'tenant'  => $tenant,
            'member'  => $invoice->member,
        ]);

        return $pdf->stream('Rechnung_' . $invoice->invoice_number . '.pdf');
    }

    // Einzelansicht der Rechnung
    public function show(Invoice $invoice)
    {
        $this->authorizeAccess($invoice);
        return view('invoices.show', compact('invoice'));
    }

    // PDF-Vorschau anzeigen
    public function pdf(Invoice $invoice)
    {
        $this->authorizeAccess($invoice);
        $invoice->load(['member', 'items']);
        $tenant = auth()->user()->tenant;

        $pdf = PDF::loadView('invoices.pdf', [
            'invoice' => $invoice,
            'tenant'  => $tenant,
            'member'  => $invoice->member,
        ]);

        return $pdf->stream('Rechnung_' . $invoice->invoice_number . '.pdf');
    }

    // Zugriff auf eigene Rechnungen beschränken
    private function authorizeAccess(Invoice $invoice)
    {
        abort_if($invoice->tenant_id !== auth()->user()->tenant_id, 403);
    }
}
