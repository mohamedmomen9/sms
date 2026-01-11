<?php

namespace Modules\Curriculum\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;
use Spatie\Translatable\HasTranslations;

class Curriculum extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'department_id',
        'name',
        'code',
        'status',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'curriculum_subject')
                    ->withPivot(['is_mandatory', 'credit_hours'])
                    ->withTimestamps();
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'curriculum_faculty');
    }
}
