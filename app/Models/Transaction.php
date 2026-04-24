<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Tenant;
use App\Scopes\CurrentTenantScope;

class Transaction extends Model
{
    protected $fillable = [
        'tenant_id',
        'date',
        'description',
        'amount',
        'account_from_id',
        'account_to_id',
        'tax_area',
        'receipt_number',
        'receipt_file',
    ];

    /**
     * 🔥 WICHTIG: Typumwandlungen
     */
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CurrentTenantScope);

        static::creating(function ($transaction) {
            if (Auth::check()) {
                $transaction->tenant_id = Auth::user()->tenant_id;
            }
        });
    }

    /**
     * Beziehung: Konto (von)
     */
    public function account_from()
    {
        return $this->belongsTo(Account::class, 'account_from_id');
    }

    /**
     * Beziehung: Konto (nach)
     */
    public function account_to()
    {
        return $this->belongsTo(Account::class, 'account_to_id');
    }

    /**
     * Beziehung: Transaktion gehört zu einem Verein (Mandant)
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope: Filter für aktuellen Mandanten
     */
    public function scopeForCurrentTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }

    /**
     * Prüft, ob es sich um eine Einnahme handelt
     */
    public function isIncome(): bool
    {
        return $this->account_to && $this->account_to->type === 'einnahme';
    }

    /**
     * Prüft, ob es sich um eine Ausgabe handelt
     */
    public function isExpense(): bool
    {
        return $this->account_from && $this->account_from->type === 'ausgabe';
    }
}