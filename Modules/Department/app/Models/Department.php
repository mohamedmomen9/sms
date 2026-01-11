<?php

namespace Modules\Department\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

use Modules\Curriculum\Models\Curriculum;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;

class Department extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

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
     * Get curricula belonging to this department
     */
    public function curricula(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }

    /**
     * Get subjects belonging to this department
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
