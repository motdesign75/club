<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'logo',
        'address',
        'zip',
        'city',
        'phone',
        'register_number',
    ];

    /**
     * Beziehung: Ein Tenant hat viele Benutzer
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Setze automatisch einen Invite-Code bei Erstellung
     */
    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant) {
            $tenant->invite_code = Str::uuid();
        });
    }
}
