<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use PDF;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Liste aller Rechnungen
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $invoices = Invoice::where('tenant_id', auth()->user()->tenant_id)
            ->with('member')
            ->latest()
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    /*
    |--------------------------------------------------------------------------
    | Formular anzeigen
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $members = Member::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('last_name')
            ->get();

        return view('invoices.create', compact('members'));
    }

    /*
    |--------------------------------------------------------------------------
    | Rechnung speichern
    |--------------------------------------------------------------------------
    */

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

        $invoice = $this->createInvoiceWithUniqueNumber([
            'tenant_id'      => auth()->user()->tenant_id,
            'member_id'      => $request->member_id,
            'invoice_date'   => $request->invoice_date,
            'discount'       => $request->discount ?? 0,
            'tax_rate'       => $request->tax_rate ?? 0,
            'status'         => 'entwurf',
        ]);

        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit'        => $item['unit'] ?? 'Stück',
                'unit_price'  => $item['unit_price'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice);
    }

    /*
    |--------------------------------------------------------------------------
    | AUTOMATISCHE MITGLIEDSBEITRÄGE
    |--------------------------------------------------------------------------
    */

    public function generateMembershipInvoices()
    {
        $tenantId = auth()->user()->tenant_id;
        $createdCount = 0;

        $members = Member::where('tenant_id', $tenantId)
            ->whereNull('exit_date')
            ->with('membership')
            ->get();

        foreach ($members as $member) {

            if (!$member->membership) {
                continue;
            }

            $amount = $member->membership->amount;
            $interval = strtolower(trim($member->membership->interval));

            if (empty($amount) || empty($interval)) {
                continue;
            }

            $validIntervals = ['monatlich', 'quartal', 'halbjahr', 'jährlich'];

            if (!in_array($interval, $validIntervals)) {
                continue;
            }

            $periods = $this->getPeriodsForInterval($interval);

            foreach ($periods as $period) {

                $exists = Invoice::where('tenant_id', $tenantId)
                    ->where('member_id', $member->id)
                    ->whereDate('period_from', $period['from'])
                    ->whereDate('period_to', $period['to'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                $now = now();

                $invoice = $this->createInvoiceWithUniqueNumber([
                    'tenant_id'    => $tenantId,
                    'member_id'    => $member->id,

                    'invoice_date' => $now,
                    'due_date'     => $now->copy()->addDays(14),

                    'period_year'  => $period['from']->year,
                    'period_from'  => $period['from'],
                    'period_to'    => $period['to'],

                    'status'       => 'open',
                ]);

                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => 'Mitgliedsbeitrag ' . $period['label'],
                    'quantity'    => 1,
                    'unit'        => $interval,
                    'unit_price'  => $amount,
                ]);

                $createdCount++;
            }
        }

        return redirect()
            ->route('invoices.index')
            ->with('success', $createdCount . ' Mitgliedsrechnungen erstellt');
    }

    /*
    |--------------------------------------------------------------------------
    | Rechnungsnummer sicher erzeugen
    |--------------------------------------------------------------------------
    */

    private function createInvoiceWithUniqueNumber(array $data): Invoice
    {
        for ($attempt = 0; $attempt < 20; $attempt++) {
            $data['invoice_number'] = $this->generateUniqueInvoiceNumber($attempt);

            try {
                return Invoice::create($data);
            } catch (QueryException $e) {
                if (!$this->isDuplicateInvoiceNumberException($e)) {
                    throw $e;
                }
            }
        }

        abort(500, 'Es konnte keine eindeutige Rechnungsnummer erzeugt werden.');
    }

    private function generateUniqueInvoiceNumber(int $attempt = 0): string
    {
        $baseNumber = Invoice::generateInvoiceNumber();

        if ($attempt === 0 && !$this->invoiceNumberExists($baseNumber)) {
            return $baseNumber;
        }

        do {
            $invoiceNumber = $baseNumber . '-' . random_int(1000, 9999);
        } while ($this->invoiceNumberExists($invoiceNumber));

        return $invoiceNumber;
    }

    private function invoiceNumberExists(string $invoiceNumber): bool
    {
        return Invoice::where('invoice_number', $invoiceNumber)->exists();
    }

    private function isDuplicateInvoiceNumberException(QueryException $e): bool
    {
        return $e->getCode() === '23000'
            && str_contains($e->getMessage(), 'invoices_invoice_number_unique');
    }

    /*
    |--------------------------------------------------------------------------
    | PERIODEN LOGIK
    |--------------------------------------------------------------------------
    */

    private function getPeriodsForInterval($interval)
    {
        $year = now()->year;
        $periods = [];

        switch ($interval) {

            case 'monatlich':
                for ($m = 1; $m <= now()->month; $m++) {
                    $from = Carbon::create($year, $m, 1)->startOfMonth();
                    $to   = (clone $from)->endOfMonth();

                    $periods[] = [
                        'from' => $from,
                        'to'   => $to,
                        'label'=> $from->format('m/Y')
                    ];
                }
                break;

            case 'quartal':
                for ($q = 1; $q <= ceil(now()->month / 3); $q++) {
                    $from = Carbon::create($year, 1, 1)->startOfYear()->addQuarters($q - 1);
                    $to   = (clone $from)->endOfQuarter();

                    $periods[] = [
                        'from' => $from,
                        'to'   => $to,
                        'label'=> 'Q' . $q . ' ' . $year
                    ];
                }
                break;

            case 'halbjahr':
                if (now()->month >= 1) {
                    $periods[] = [
                        'from' => Carbon::create($year, 1, 1),
                        'to'   => Carbon::create($year, 6, 30),
                        'label'=> 'H1 ' . $year
                    ];
                }

                if (now()->month >= 7) {
                    $periods[] = [
                        'from' => Carbon::create($year, 7, 1),
                        'to'   => Carbon::create($year, 12, 31),
                        'label'=> 'H2 ' . $year
                    ];
                }
                break;

            case 'jährlich':
            default:
                $periods[] = [
                    'from' => Carbon::create($year, 1, 1),
                    'to'   => Carbon::create($year, 12, 31),
                    'label'=> $year
                ];
                break;
        }

        return $periods;
    }

    /*
    |--------------------------------------------------------------------------
    | Einzelansicht
    |--------------------------------------------------------------------------
    */

    public function show(Invoice $invoice)
    {
        $this->authorizeAccess($invoice);

        $invoice->load(['member', 'items', 'payments']);

        return view('invoices.show', compact('invoice'));
    }

    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Sicherheit
    |--------------------------------------------------------------------------
    */

    private function authorizeAccess(Invoice $invoice)
    {
        abort_if($invoice->tenant_id !== auth()->user()->tenant_id, 403);
    }
}
