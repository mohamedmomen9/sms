<?php

namespace Modules\Users\Models;

use App\Traits\HasAcademicScope;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HasAcademicScope;

    protected static function newFactory()
    {
        return \Modules\Users\Database\Factories\UserFactory::new();
    }

    /**
     * The attributes that are mass assignable.
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

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function getFilamentName(): string
    {
        return (string) ($this->display_name ?? $this->username ?? $this->first_name ?? 'Admin');
    }

    public function getNameAttribute(): string
    {
        return (string) ($this->display_name ?? $this->username ?? $this->first_name ?? 'Admin');
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }

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
