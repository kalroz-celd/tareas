<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordEntry extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'category',
        'title',
        'username',
        'secret',
        'url',
        'notes',
    ];

    protected $casts = [
        'secret' => 'encrypted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
