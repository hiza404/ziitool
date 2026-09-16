<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'plan',
        'is_active',
        'customer_name',
        'customer_email',
        'used_at',
        'expires_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }
}
