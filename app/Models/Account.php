<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CurrentTenantScope;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'type',
        'tax_area',
        'iban',
        'bic',
        'description',
        'active',
        'online',
        'balance_start',
        'balance_date',
        'balance_current',
        'tenant_id',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CurrentTenantScope);

        static::creating(function ($account) {
            if (Auth::check()) {
                $account->tenant_id = Auth::user()->tenant_id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeForCurrentTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }

    // Buchungen, bei denen dieses Konto als "von" verwendet wurde (Ausgabe)
    public function transactionsFrom()
    {
        return $this->hasMany(Transaction::class, 'account_from_id');
    }

    // Buchungen, bei denen dieses Konto als "an" verwendet wurde (Einnahme)
    public function transactionsTo()
    {
        return $this->hasMany(Transaction::class, 'account_to_id');
    }

    // ✅ NEU: Methode zur Berechnung des aktuellen Saldos
    public function updateBalance(): void
    {
        $sumIn = $this->transactionsTo()->sum('amount');
        $sumOut = $this->transactionsFrom()->sum('amount');

        $this->balance_current = ($this->balance_start ?? 0) + $sumIn - $sumOut;
        $this->save();
    }

    public function getTaxAreaLabelAttribute(): string
    {
        return match ($this->tax_area) {
            'ideell' => 'Ideeller Bereich',
            'zweckbetrieb' => 'Zweckbetrieb',
            'wirtschaftlich' => 'Wirtschaftlicher Geschäftsbetrieb',
            default => 'Unbekannt',
        };
    }
}
