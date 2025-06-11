<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

        // Block: Mitgliedschaft
        'member_id',
        'entry_date',
        'exit_date',
        'cancellation_date',
        'membership_id',

        // Block: Kommunikation
        'email',
        'mobile',
        'landline',

        // Block: Adresse
        'street',
        'address_extra',
        'zip',
        'city',
        'country',
        'care_of',
    ];

    // Beziehung: Mitglied gehört zu einem Verein (Mandant)
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Beziehung: Mitglied hat eine Mitgliedschaft
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    // Scope für aktuellen Mandanten
    public function scopeForCurrentTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }
}
