<?php

namespace Modules\Students\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Campus\Models\Campus;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;


class Student extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected static function newFactory()
    {
        return \Modules\Students\Database\Factories\StudentFactory::new();
    }

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

    public function images()
    {
        return $this->hasMany(StudentImage::class);
    }

    public function guardians()
    {
        return $this->belongsToMany(\Modules\Family\Models\Guardian::class, 'parent_student', 'student_id', 'parent_id')
            ->withPivot('relationship_type');
    }
}
