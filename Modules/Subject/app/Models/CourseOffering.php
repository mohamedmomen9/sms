<?php

namespace Modules\Subject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Academic\Models\Term;
use Modules\Campus\Models\Room;
use Modules\Students\Models\CourseEnrollment;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;

class CourseOffering extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'term_id',
        'section_number',
        'capacity',
        'room_id',
        'schedule_json',
    ];

    protected $casts = [
        'schedule_json' => 'array',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * Many-to-many relationship with teachers (instructors)
     * Supports multiple instructors per course offering
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'course_offering_teacher')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    /**
     * Get the primary instructor for this offering
     */
    public function primaryInstructor(): ?Teacher
    {
        return $this->teachers()->wherePivot('is_primary', true)->first();
    }

    /**
     * Get all instructor names as comma-separated string
     */
    public function getInstructorNamesAttribute(): string
    {
        return $this->teachers->pluck('name')->implode(', ') ?: '-';
    }

    /**
     * @deprecated Use teachers() relationship instead
     * Kept for backward compatibility during transition
     */
    public function teacher(): BelongsTo
    {
        // Returns null belongsTo for compatibility - use teachers() instead
        return $this->belongsTo(Teacher::class, 'id', 'id')->whereRaw('1=0');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_enrollments', 'course_offering_id', 'student_id')
            ->withPivot('grade', 'status', 'enrolled_at');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(CourseSchedule::class)->ordered();
    }
}
