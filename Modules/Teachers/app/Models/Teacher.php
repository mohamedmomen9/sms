<?php

namespace Modules\Teachers\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Campus\Models\Campus;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;
use Spatie\Permission\Traits\HasRoles;

class Teacher extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'qualification',
        'campus_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getGuardName(): string
    {
        return 'teacher';
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'faculty_teacher')
            ->withTimestamps();
    }

    public function subjects()
    {
        // Dynamic subjects based on course offerings
        return $this->belongsToMany(Subject::class, 'course_offerings', 'teacher_id', 'subject_id')
            ->distinct();
    }

    public function offerings()
    {
        return $this->hasMany(\Modules\Subject\Models\CourseOffering::class);
    }

    public function getAccessibleSubjectsAttribute()
    {
        $facultyIds = $this->faculties()->pluck('faculties.id')->toArray();
        
        if (empty($facultyIds)) {
            return $this->subjects;
        }

        return Subject::where(function ($query) use ($facultyIds) {
            $query->whereIn('faculty_id', $facultyIds)
                ->orWhereHas('department', function ($q) use ($facultyIds) {
                    $q->whereIn('faculty_id', $facultyIds);
                });
        })->get();
    }

    public function canAccessSubject(Subject $subject): bool
    {
        if ($this->subjects()->where('subjects.id', $subject->id)->exists()) {
            return true;
        }

        $facultyIds = $this->faculties()->pluck('faculties.id')->toArray();
        
        if (empty($facultyIds)) {
            return false;
        }

        if ($subject->faculty_id && in_array($subject->faculty_id, $facultyIds)) {
            return true;
        }

        if ($subject->department_id && $subject->department) {
            return in_array($subject->department->faculty_id, $facultyIds);
        }

        return false;
    }

    public function canAccessFaculty(Faculty $faculty): bool
    {
        return $this->faculties()->where('faculties.id', $faculty->id)->exists();
    }

    public function getFilamentName(): string
    {
        return $this->name ?? 'Teacher';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'teacher';
    }
}
