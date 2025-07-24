<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Protocol extends Model
{
    use HasFactory;

    /**
     * Die Felder, die massenweise befüllbar sind.
     */
    protected $fillable = [
        'tenant_id',
        'user_id',
        'title',
        'type',
        'location',
        'start_time',
        'end_time',
        'content',
    ];

    /**
     * Zugehöriger Mandant (Verein)
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Ersteller des Protokolls (z. B. Vorstandsmitglied)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Teilnehmer des Protokolls (Mitglieder)
     */
    public function participants()
    {
        return $this->belongsToMany(Member::class, 'protocol_member')
            ->withTimestamps();
    }
}
