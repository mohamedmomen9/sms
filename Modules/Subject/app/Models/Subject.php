<?php

namespace Modules\Subject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

use Modules\Curriculum\Models\Curriculum;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Modules\Users\Models\User;

class Subject extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'faculty_id',
        'department_id',
        'code',
        'name',
    ];

    public function prerequisites()
    {
        return $this->belongsToMany(Subject::class, 'subject_prerequisites', 'subject_id', 'prerequisite_id')
            ->withTimestamps();
    }

    public function prerequisiteFor()
    {
        return $this->belongsToMany(Subject::class, 'subject_prerequisites', 'prerequisite_id', 'subject_id')
            ->withTimestamps();
    }

    /**
     * Get the faculty this subject belongs to (direct relationship)
     */
    public function curricula()
    {
        return $this->belongsToMany(Curriculum::class, 'curriculum_subject')
                    ->withPivot('is_mandatory')
                    ->withTimestamps();
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the department this subject belongs to
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function offerings()
    {
        return $this->hasMany(\Modules\Subject\Models\CourseOffering::class);
    }

    /**
     * Get the effective faculty (either direct or through department)
     */
    public function getEffectiveFacultyAttribute(): ?\Modules\Faculty\Models\Faculty
    {
        if ($this->faculty_id) {
            return $this->faculty;
        }
        
        if ($this->department_id && $this->department) {
            return $this->department->faculty;
        }

        return null;
    }
}
