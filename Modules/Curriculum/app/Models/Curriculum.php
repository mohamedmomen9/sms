<?php

namespace Modules\Curriculum\Models;

use Illuminate\Database\Eloquent\Model;
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



    public function department()
    {
        return $this->belongsTo(\Modules\Department\Models\Department::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(\Modules\Subject\Models\Subject::class, 'curriculum_subject')
                    ->withPivot(['is_mandatory', 'credit_hours'])
                    ->withTimestamps();
    }

    public function faculties()
    {
        return $this->belongsToMany(\Modules\Faculty\Models\Faculty::class, 'curriculum_faculty');
    }

    public $proxied_subjects = null;

    protected static function booted()
    {
        static::saved(function ($model) {
            if (is_array($model->proxied_subjects)) {
                $activeSubjectIds = [];
                
                foreach ($model->proxied_subjects as $group) {
                    if (isset($group['subjects']) && is_array($group['subjects'])) {
                        foreach ($group['subjects'] as $subjectData) {
                            $subjectId = $subjectData['id'] ?? null;
                            if ($subjectId) {
                                $activeSubjectIds[$subjectId] = [
                                    'is_mandatory' => $subjectData['is_mandatory'] ?? true,
                                    'credit_hours' => $subjectData['credit_hours'] ?? 3.0,
                                ];
                            }
                        }
                    }
                }

                $model->subjects()->sync($activeSubjectIds);
            }
        });
    }
}
