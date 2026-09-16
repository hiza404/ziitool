<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    /**
     * Retrieve a setting value by key with a fallback default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 300, function () use ($key, $default) {
            $record = static::where('key', $key)->first();

            return $record ? $record->value : $default;
        });
    }

    /**
     * Store or update a setting value.
     */
    public static function set(string $key, mixed $value, string $group = 'general'): self
    {
        Cache::forget("setting_{$key}");

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'group' => $group]
        );
    }
}
