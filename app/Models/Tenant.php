<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'iban',
        'bic',
        'bank_name',
        'chairman',
        'letterhead',
        'pdf_template',
        'chairman_name',
        'use_letterhead',

        // ➕ SMTP Felder
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant) {
            // Einladungscode wird automatisch vergeben, wenn er fehlt
            if (!$tenant->invite_code) {
                $tenant->invite_code = Str::uuid();
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function invitationCode()
    {
        return $this->hasOne(InvitationCode::class);
    }
}
