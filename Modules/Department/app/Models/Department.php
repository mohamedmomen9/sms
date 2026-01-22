<?php

namespace Modules\Department\Models;

use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Modules\Curriculum\Models\Curriculum;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Department\Database\Factories\DepartmentFactory;

class Department extends Model
{
    use HasTranslations, HasFactory;

    protected static function newFactory()
    {
        return DepartmentFactory::new();
    }

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
    public function curricula(): BelongsToMany
    {
        return $this->belongsToMany(Curriculum::class, 'curriculum_department');
    }

    /**
     * Get subjects belonging to this department
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
