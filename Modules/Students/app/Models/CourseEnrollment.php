<?php

namespace Modules\Students\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Students\Database\Factories\CourseEnrollmentFactory;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'course_offering_id', 'grade', 'status', 'enrolled_at'];

    public function student()
    {
        return $this->belongsTo(\Modules\Students\Models\Student::class);
    }

    public function courseOffering()
    {
        return $this->belongsTo(\Modules\Subject\Models\CourseOffering::class);
    }

    // protected static function newFactory(): CourseEnrollmentFactory
    // {
    //     // return CourseEnrollmentFactory::new();
    // }
}
