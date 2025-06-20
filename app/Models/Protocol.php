<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Protocol extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'title',
        'type',
        'content',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Neue Beziehung: Teilnehmer (Mitglieder)
    public function participants()
    {
        return $this->belongsToMany(Member::class, 'protocol_member')
            ->withTimestamps();
    }
}
