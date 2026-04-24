<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Storage;

class ProjectDocument extends Model
{
    use HasUlids;

    public $incrementing = false;
    protected $keyType   = 'string';

    protected $fillable = [
        'tenant_id',
        'project_id',
        'user_id',
        'disk',
        'path',
        'original_name',
        'size',
        'mime_type',
    ];

    // Beziehungen
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // URL zum Download/Anzeigen (für public-disk)
    public function getUrlAttribute(): ?string
    {
        return $this->disk ? Storage::disk($this->disk)->url($this->path) : null;
    }
}
