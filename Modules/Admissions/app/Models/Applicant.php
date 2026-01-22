<?php

namespace Modules\Admissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Faculty\Models\Faculty;
use Modules\Academic\Models\Term;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'national_id',
        'email',
        'phone',
        'faculty_id',
        'applied_term_id',
        'status', // new, reviewing, accepted, rejected
        'documents', // json
        'notes',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function appliedTerm()
    {
        return $this->belongsTo(Term::class, 'applied_term_id');
    }
}
