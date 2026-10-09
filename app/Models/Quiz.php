<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'code',
    'user_id',
    'title',
    'description',
    'is_public',
    'total_questions',
    'questions',
    'attempts_count',
])]
class Quiz extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'total_questions' => 'integer',
            'attempts_count' => 'integer',
            'questions' => 'array',
        ];
    }

    /**
     * User who created this quiz.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique quiz code.
     */
    public static function generateCode(): string
    {
        do {
            $code = 'ZT-'.strtoupper(Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
