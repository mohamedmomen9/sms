<?php

namespace App\Models;

use App\Traits\HasAcademicScope;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HasAcademicScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_admin',
        'faculty_id',
        'subject_id',
        'display_name',
        'first_name',
        'last_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }

    /**
     * Get the faculty the user belongs to
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the Subjects the user belongs to (Many-to-Many)
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    /**
     * Get the subject the user belongs to (Legacy single subject)
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
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
     * Get the user's scope type as a string for display
     */
    public function getScopeTypeAttribute(): string
    {
        if ($this->isAdmin()) {
            return 'Admin (Global Access)';
        }
        if ($this->isScopedToSubject()) {
            return 'Subject';
        }
        if ($this->isScopedToFaculty()) {
            return 'Faculty';
        }
        return 'None';
    }

    /**
     * Get a human-readable description of the user's scope
     */
    public function getScopeDescriptionAttribute(): string
    {
        if ($this->isAdmin()) {
            return 'Full access to system';
        }

        if ($this->isScopedToSubject() && $this->subject) {
            $name = $this->subject->name ?? 'Unknown';
            return "Subject: {$name}";
        }

        if ($this->isScopedToFaculty() && $this->faculty) {
            return "Faculty: {$this->faculty->name}";
        }

        return 'No scope assigned';
    }
}
