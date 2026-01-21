<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class StudentWishlist extends Model
{
    protected $fillable = [
        'student_id',
        'term_id',
        'item_ids',
        'item_names',
    ];

    protected $casts = [
        'item_ids' => 'array',
        'item_names' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }
}
