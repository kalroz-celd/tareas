<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    public const STATUS_LABELS = [
        'planning' => 'Planificación',
        'active' => 'Activo',
        'on_hold' => 'En pausa',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
    ];

    public const STATUS_BADGE_CLASSES = [
        'planning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
        'active' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-200',
        'on_hold' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
        'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200',
        'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-200',
    ];

    public const PRIORITY_LABELS = [
        'low' => 'Baja',
        'medium' => 'Media',
        'high' => 'Alta',
        'urgent' => 'Urgente',
    ];

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

    
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return self::STATUS_BADGE_CLASSES[$this->status]
            ?? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200';
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITY_LABELS[$this->priority] ?? ucfirst((string) $this->priority);
    }

    public function getPriorityBadgeClassesAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
            'medium' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-200',
            'high' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200',
            'urgent' => 'bg-red-200 text-red-900 dark:bg-red-900/50 dark:text-red-100',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
        };
    }
}
