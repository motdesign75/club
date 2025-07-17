<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CurrentTenantScope;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',

        // Block: Mitglied
        'gender',
        'salutation',
        'title',
        'first_name',
        'last_name',
        'organization',
        'birthday',
        'photo',

        // Block: Mitgliedschaft
        'member_id',
        'entry_date',
        'exit_date',
        'termination_date',
        'membership_id',
        'membership_amount',
        'membership_interval',

        // Block: Kommunikation
        'email',
        'mobile',
        'landline',

        // Block: Adresse
        'street',
        'address_addition',
        'zip',
        'city',
        'country',
        'care_of',
    ];

    protected $casts = [
        'birthday'         => 'date',
        'entry_date'       => 'date',
        'exit_date'        => 'date',
        'termination_date' => 'date',
    ];

    protected $appends = [
        'full_name',
        'country_name',
        'status',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CurrentTenantScope);

        static::creating(function ($member) {
            if (Auth::check()) {
                $member->tenant_id = Auth::user()->tenant_id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function customValues()
    {
        return $this->hasMany(CustomMemberValue::class);
    }

    public function protocols()
    {
        return $this->belongsToMany(Protocol::class, 'protocol_member')->withTimestamps();
    }

    public function scopeForCurrentTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }

    public function getFullNameAttribute()
    {
        $parts = array_filter([$this->title, $this->first_name, $this->last_name]);
        return implode(' ', $parts);
    }

    public function getCountryNameAttribute()
    {
        return config('countries.list')[$this->country] ?? $this->country;
    }

    /**
     * Automatischer Status eines Mitglieds
     */
    public function getStatusAttribute(): string
    {
        $today = now();

        if ($this->exit_date && $this->exit_date->isPast()) {
            return 'ehemalig';
        }

        if ($this->entry_date && $this->entry_date->isFuture()) {
            return 'zukünftig';
        }

        if ($this->entry_date && (!$this->exit_date || $this->exit_date->isFuture())) {
            return 'aktiv';
        }

        return 'zukünftig';
    }

    /**
     * Tags eines Mitglieds (z. B. Eltern, Vorstand, Jugendgruppe etc.)
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
