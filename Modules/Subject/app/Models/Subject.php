<?php

namespace Modules\Subject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Subject extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'faculty_id',
        'department_id',
        'curriculum',
        'code',
        'name',
        'category',
        'type',
        'max_hours',
        'curriculum_id',
    ];



    /**
     * Get the curriculum group this subject belongs to
     * Naming it curriculumGroup to avoid conflict with legacy curriculum string column
     */
    public function curriculumGroup(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    /**
     * Get the faculty this subject belongs to (direct relationship)
     */
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

    /**
     * Get users assigned to this subject
     */
    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class);
    }

    /**
     * Get the effective faculty (either direct or through department)
     */
    public function getEffectiveFacultyAttribute(): ?Faculty
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
