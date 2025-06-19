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
        'tenant_id',
    ];

    /**
     * Global Scope für Mandantenschutz + tenant_id automatisch setzen
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new CurrentTenantScope);

        static::creating(function ($account) {
            if (Auth::check()) {
                $account->tenant_id = Auth::user()->tenant_id;
            }
        });
    }

    /**
     * Beziehung: Konto gehört zu einem Verein (Mandant)
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Lokaler Scope: aktueller Mandant (optional, zusätzlich zu globalem Scope)
     */
    public function scopeForCurrentTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }
}
