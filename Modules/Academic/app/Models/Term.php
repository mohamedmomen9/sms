<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Academic\Database\Factories\TermFactory;

class Term extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['academic_year_id', 'name', 'code', 'start_date', 'end_date', 'is_active', 'type'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function courseOfferings()
    {
        return $this->hasMany(\Modules\Subject\Models\CourseOffering::class);
    }

    // protected static function newFactory(): TermFactory
    // {
    //     // return TermFactory::new();
    // }
}
