<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'faculty_id',
        'department_id',
        'curriculum',
        'code',
        'name_ar',
        'name_en',
        'category',
        'type',
        'max_hours',
    ];

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
        return $this->hasMany(User::class);
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

    /**
     * Get the effective university through the faculty chain
     */
    public function getEffectiveUniversityAttribute(): ?University
    {
        $faculty = $this->effective_faculty;
        return $faculty?->university;
    }
}
