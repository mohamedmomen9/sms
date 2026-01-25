<?php

namespace Modules\Students\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTutorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutorial_key',
        'completed_at',
        'meta',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'meta' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public static function isCompleted(int $studentId, string $key): bool
    {
        return static::where('student_id', $studentId)
            ->where('tutorial_key', $key)
            ->exists();
    }

    public static function markCompleted(int $studentId, string $key, array $meta = []): self
    {
        return static::updateOrCreate(
            ['student_id' => $studentId, 'tutorial_key' => $key],
            ['completed_at' => now(), 'meta' => $meta]
        );
    }
}
