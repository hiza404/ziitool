<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin', 'is_pro', 'pro_plan', 'pro_expires_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_pro' => 'boolean',
            'pro_expires_at' => 'datetime',
        ];
    }

    /**
     * Orders placed by this user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Pro licenses owned by this user.
     */
    public function proLicenses(): HasMany
    {
        return $this->hasMany(ProLicense::class);
    }

    /**
     * Check whether the user currently has an active Pro membership.
     */
    public function isPro(): bool
    {
        if ($this->is_admin) {
            return true;
        }

        if (! $this->is_pro) {
            return false;
        }

        if ($this->pro_expires_at === null) {
            return true;
        }

        return $this->pro_expires_at->isFuture();
    }
}
