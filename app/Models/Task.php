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

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITY_LABELS[$this->priority] ?? ucfirst((string) $this->priority);
    }
}
