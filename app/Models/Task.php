<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public const STATUS_LABELS = [
        'todo' => 'Por hacer',
        'doing' => 'En progreso',
        'done' => 'Hecha',
        'blocked' => 'Bloqueada',
    ];

    public const STATUS_BADGE_CLASSES = [
        'todo' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
        'doing' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-200',
        'done' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200',
        'blocked' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-200',
    ];

    public const PRIORITY_LABELS = [
        'low' => 'Baja',
        'medium' => 'Media',
        'high' => 'Alta',
        'urgent' => 'Urgente',
    ];

    protected $fillable = [
        'project_id','title','description','status','priority','due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class);
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
