<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'priority',
        'start_date',
        'due_date',
        'is_archived',
        'client_id',
        'payment_due_date',
        'amount',
        'currency',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'due_date'     => 'date',
        'is_archived'  => 'boolean',
        'payment_due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
