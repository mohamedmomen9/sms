<?php

namespace Modules\Disciplinary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class Grievance extends Model
{
    protected $fillable = [
        'student_id',
        'term_id',
        'violation_type',
        'violation_description',
        'decision_text',
        'dean_date',
        'grievance_text',
        'grievance_decision',
        'grievance_dean_date',
        'approval_status',
        'grievance_approval_status',
    ];

    protected $casts = [
        'dean_date' => 'date',
        'grievance_dean_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function canSubmitAppeal(): bool
    {
        return $this->grievance_approval_status === null && empty($this->grievance_text);
    }
}
