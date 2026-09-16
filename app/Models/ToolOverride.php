<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'is_active',
        'custom_title',
        'custom_badge',
        'custom_desc',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
