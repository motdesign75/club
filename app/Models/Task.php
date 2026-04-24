<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Task extends Model
{
    use HasUlids;

    public $incrementing = false;
    protected $keyType   = 'string';

    protected $fillable = [
        'tenant_id',
        'project_id',
        'title',
        'description',
        'plan_start',
        'plan_end',
        'actual_start',
        'actual_end',
        'status',
        'percent_done',
        'assignee_id',
        'priority',
        'type',
    ];

    protected $casts = [
        'plan_start'   => 'date',
        'plan_end'     => 'date',
        'actual_start' => 'date',
        'actual_end'   => 'date',
        'percent_done' => 'integer',
        'priority'     => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
