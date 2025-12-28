<?php

namespace App\Models;

use App\Traits\HasAcademicScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;

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
        'university_id',
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
     * Get the university the user belongs to (direct assignment)
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the faculty the user belongs to
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the subject the user belongs to
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
        if ($this->isScopedToUniversity()) {
            return 'University';
        }
        return 'None';
    }

    /**
     * Get a human-readable description of the user's scope
     */
    public function getScopeDescriptionAttribute(): string
    {
        if ($this->isAdmin()) {
            return 'Full access to all universities';
        }

        if ($this->isScopedToSubject() && $this->subject) {
            $name = $this->subject->name_en ?? $this->subject->name_ar ?? 'Unknown';
            return "Subject: {$name}";
        }

        if ($this->isScopedToFaculty() && $this->faculty) {
            return "Faculty: {$this->faculty->name}";
        }

        if ($this->isScopedToUniversity() && $this->university) {
            return "University: {$this->university->name}";
        }

        return 'No scope assigned';
    }
}
