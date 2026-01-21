<?php

namespace Modules\Evaluation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;
use Modules\Teachers\Models\Teacher;

class Evaluation extends Model
{
    protected $fillable = [
        'student_id',
        'assessment_id',
        'term_id',
        'course_code',
        'instructor_id',
        'responses',
        'submitted_at',
    ];

    protected $casts = [
        'responses' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'instructor_id');
    }
}
