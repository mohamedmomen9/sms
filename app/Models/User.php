<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_admin',
        'display_name',
        'first_name',
        'last_name',
        'faculty_id',
    ];

    /**
     * Get the faculty the user belongs to
     */
    public function faculty(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the user's display name for Filament
     */
    public function getFilamentName(): string
    {
        return (string) ($this->display_name ?? $this->username ?? $this->first_name ?? 'Admin');
    }

    /**
     * Helper attribute for name access
     */
    public function getNameAttribute(): string
    {
        return (string) ($this->display_name ?? $this->username ?? $this->first_name ?? 'Admin');
    }

    /**
     * Determine if the user can access the Filament panel
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true || $this->hasRole('Super Admin');
    }
}
