<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiEndpoint extends Model
{
    protected $fillable = [
        'name',
        'method',
        'path',
        'group',
        'description',
        'parameters',
        'response',
        'auth_required',
        'is_active',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
            'response' => 'array',
            'auth_required' => 'boolean',
            'is_active' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public static function getMethodColors(): array
    {
        return [
            'GET' => 'success',
            'POST' => 'primary',
            'PUT' => 'warning',
            'PATCH' => 'info',
            'DELETE' => 'danger',
        ];
    }

    public function getMethodColorAttribute(): string
    {
        return self::getMethodColors()[$this->method] ?? 'secondary';
    }
}
