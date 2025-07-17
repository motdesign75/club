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
        'status',
        'discount',     // Rabatt in Prozent (optional)
        'tax_rate',     // Steuer in Prozent (optional)
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

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
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

    // Zwischensumme (Summe aller Positionen ohne Rabatt/USt.)
    public function getSubtotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }

    // Rabattbetrag in €
    public function getDiscountAmount(): float
    {
        $discount = $this->discount ?? 0;
        return round(($this->getSubtotal() * $discount) / 100, 2);
    }

    // Nettobetrag nach Abzug des Rabatts
    public function getNetTotal(): float
    {
        return round($this->getSubtotal() - $this->getDiscountAmount(), 2);
    }

    // Steuerbetrag in €
    public function getTaxAmount(): float
    {
        $tax = $this->tax_rate ?? 0;
        return round(($this->getNetTotal() * $tax) / 100, 2);
    }

    // Gesamtsumme (Netto + USt.)
    public function getTotal(): float
    {
        return round($this->getNetTotal() + $this->getTaxAmount(), 2);
    }
}
