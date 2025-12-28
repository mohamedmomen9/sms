<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class University extends Model
{
    protected $fillable = [
        'code',
        'name',
        'logo',
    ];

    /**
     * Get all campuses for this university
     */
    public function campuses(): HasMany
    {
        return $this->hasMany(Campus::class);
    }

    /**
     * Get all faculties for this university
     */
    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }

    /**
     * Get users directly assigned to this university
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all departments through faculties
     */
    public function departments(): HasManyThrough
    {
        return $this->hasManyThrough(Department::class, Faculty::class);
    }

    /**
     * Get all subjects count for this university
     */
    public function getSubjectsCountAttribute(): int
    {
        return Subject::whereHas('faculty', function ($q) {
            $q->where('university_id', $this->id);
        })->orWhereHas('department.faculty', function ($q) {
            $q->where('university_id', $this->id);
        })->count();
    }
}
