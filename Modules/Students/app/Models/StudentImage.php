<?php

namespace Modules\Students\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'image_type', // profile, academic, national_id
        'image_path',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
