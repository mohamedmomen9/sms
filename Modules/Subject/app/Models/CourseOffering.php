<?php

namespace Modules\Subject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseOffering extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'term_id', 'teacher_id', 'section_number', 'capacity', 'room_id', 'schedule_json'];

    protected $casts = [
        'schedule_json' => 'array',
    ];

    public function room()
    {
        return $this->belongsTo(\Modules\Campus\Models\Room::class);
    }

    public function subject()
    {
        return $this->belongsTo(\Modules\Subject\Models\Subject::class);
    }

    public function term()
    {
        return $this->belongsTo(\Modules\Academic\Models\Term::class);
    }

    public function teacher()
    {
        return $this->belongsTo(\Modules\Teachers\Models\Teacher::class);
    }

    public function enrollments()
    {
        return $this->hasMany(\Modules\Students\Models\CourseEnrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(\Modules\Students\Models\Student::class, 'course_enrollments', 'course_offering_id', 'student_id')
            ->withPivot('grade', 'status', 'enrolled_at');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(CourseSchedule::class)->ordered();
    }
}
