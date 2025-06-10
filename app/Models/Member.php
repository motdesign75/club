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

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeForCurrentTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }
}
