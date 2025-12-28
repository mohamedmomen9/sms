<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Faculty extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'campus_id',
        'code',
        'name',
    ];



    /**
     * Get the campus this faculty belongs to (optional)
     */
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Get all departments in this faculty
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get subjects directly belonging to this faculty
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get users assigned to this faculty
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all subjects including those through departments
     */
    public function getAllSubjectsAttribute()
    {
        $directSubjects = $this->subjects;
        
        $departmentSubjects = Subject::whereHas('department', function ($q) {
            $q->where('faculty_id', $this->id);
        })->get();
        
        return $directSubjects->merge($departmentSubjects);
    }
}
