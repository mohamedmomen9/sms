<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'faculty_id',
        'code',
        'name',
        'status',
    ];

    /**
     * Get the faculty this department belongs to
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get subjects belonging to this department
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the university through faculty
     */
    public function getUniversityAttribute(): ?University
    {
        return $this->faculty?->university;
    }

    /**
     * Get the university ID through faculty
     */
    public function getUniversityIdAttribute(): ?int
    {
        return $this->faculty?->university_id;
    }
}
