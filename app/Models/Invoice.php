<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'member_id',
        'invoice_number',
        'invoice_date',
        'amount',
        'description',
        'status',
    ];

    // Beziehungen
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Rechnungsnummer automatisch aus Nummernkreis generieren
    public static function generateInvoiceNumber(): string
    {
        $tenantId = auth()->user()->tenant_id;

        $range = \App\Models\InvoiceNumberRange::where('tenant_id', $tenantId)
            ->where('type', 'beitrag')
            ->first();

        if (!$range) {
            throw new \Exception("Kein Nummernkreis vom Typ 'beitrag' vorhanden.");
        }

        $range->current_number++;
        $range->save();

        return $range->prefix .
            str_pad($range->current_number, 4, '0', STR_PAD_LEFT) .
            $range->suffix;
    }
}
