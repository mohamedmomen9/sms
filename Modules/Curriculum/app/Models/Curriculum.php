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
        return $this->hasMany(\Modules\Subject\Models\Subject::class);
    }
}
