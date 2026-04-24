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
        'due_date',

        'period_year',
        'period_from',
        'period_to',

        'status',
        'paid_at',

        'discount',
        'tax_rate',

        'total',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Nummernkreis
    |--------------------------------------------------------------------------
    */

    public static function generateInvoiceNumber(): string
    {
        $tenantId = auth()->user()->tenant_id;

        $range = \App\Models\InvoiceNumberRange::where('tenant_id', $tenantId)
            ->where('type', 'beitrag')
            ->first();

        // ✅ Fallback wenn kein Nummernkreis vorhanden
        if (!$range) {
            return 'R-' . now()->format('YmdHis');
        }

        $range->current_number++;
        $range->save();

        return $range->prefix .
            str_pad($range->current_number, 4, '0', STR_PAD_LEFT) .
            $range->suffix;
    }


    /*
    |--------------------------------------------------------------------------
    | Berechnungen
    |--------------------------------------------------------------------------
    */

    public function getSubtotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }


    public function getDiscountAmount(): float
    {
        $discount = $this->discount ?? 0;

        return round(
            ($this->getSubtotal() * $discount) / 100,
            2
        );
    }


    public function getNetTotal(): float
    {
        return round(
            $this->getSubtotal() - $this->getDiscountAmount(),
            2
        );
    }


    public function getTaxAmount(): float
    {
        $tax = $this->tax_rate ?? 0;

        return round(
            ($this->getNetTotal() * $tax) / 100,
            2
        );
    }


    public function getTotal(): float
    {
        return round(
            $this->getNetTotal() + $this->getTaxAmount(),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helper
    |--------------------------------------------------------------------------
    */

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isDraft(): bool
    {
        return $this->status === 'entwurf';
    }


    /*
    |--------------------------------------------------------------------------
    | Zahlung setzen
    |--------------------------------------------------------------------------
    */

    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->paid_at = now();
        $this->save();
    }
}