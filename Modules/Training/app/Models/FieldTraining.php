<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class FieldTraining extends Model
{
    protected $fillable = [
        'student_id',
        'opportunity_id',
        'term_id',
        'status',
        'supervisor_notes',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(TrainingOpportunity::class, 'opportunity_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
