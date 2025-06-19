<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CurrentTenantScope;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'start',
        'end',
        'is_public',
        'tenant_id',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'is_public' => 'boolean',
    ];

    /**
     * Global Scope & automatisches Setzen von tenant_id
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new CurrentTenantScope);

        static::creating(function ($event) {
            if (Auth::check()) {
                $event->tenant_id = Auth::user()->tenant_id;
            }
        });
    }

    /**
     * Beziehung: Event gehört zu einem Verein
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Optionaler lokaler Scope für aktuellen Verein
     */
    public function scopeForCurrentTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }
}
