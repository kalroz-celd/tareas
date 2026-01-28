<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSecretAttribute($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function setSecretAttribute($value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['secret'] = null;
            return;
        }

        $this->attributes['secret'] = Crypt::encryptString($value);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
