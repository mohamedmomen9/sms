<?php

namespace Modules\Students\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'image_pose_center',
        'image_pose_left',
        'image_pose_right',
        'image_pose_down',
        'status', // pending, verified, rejected
        'rejection_reason',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
