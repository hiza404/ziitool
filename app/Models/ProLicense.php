<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'plan',
        'is_active',
        'customer_name',
        'customer_email',
        'used_at',
        'expires_at',
        'notes',
    ];

    /**
     * User who owns this license.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }
}
