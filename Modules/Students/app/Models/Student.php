<?php

namespace Modules\Students\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Campus\Models\Campus;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;


class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id',
        'date_of_birth',
        'campus_id',
        'school_id',
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }


    public function school()
    {
        return $this->belongsTo(Faculty::class, 'school_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subjects()
    {
        // Dynamic subjects based on current enrollments
        return Subject::whereHas('offerings.enrollments', function ($query) {
            $query->where('student_id', $this->id);
        });
    }

    public function enrollments()
    {
        return $this->hasMany(\Modules\Students\Models\CourseEnrollment::class);
    }

    public function currentClasses()
    {
        return $this->enrollments()->whereHas('courseOffering.term', function ($query) {
            $query->where('is_active', true);
        });
    }


}
