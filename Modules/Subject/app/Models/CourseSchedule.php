<?php

namespace Modules\Subject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Teachers\Models\Teacher;

class CourseSchedule extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Modules\Subject\Database\Factories\CourseScheduleFactory::new();
    }

    protected $fillable = [
        'course_offering_id',
        'session_type_id',
        'teacher_id',
        'day',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    /**
     * Days of the week options
     */
    public const DAYS = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ];

    /**
     * Day order for sorting
     */
    public const DAY_ORDER = [
        'Sunday' => 1,
        'Monday' => 2,
        'Tuesday' => 3,
        'Wednesday' => 4,
        'Thursday' => 5,
        'Friday' => 6,
        'Saturday' => 7,
    ];

    /**
     * Get the course offering this schedule belongs to
     */
    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }

    /**
     * Get the session type for this schedule
     */
    public function sessionType(): BelongsTo
    {
        return $this->belongsTo(SessionType::class);
    }

    /**
     * Get the instructor assigned to this specific session
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the day order for sorting
     */
    public function getDayOrderAttribute(): int
    {
        return self::DAY_ORDER[$this->day] ?? 99;
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute(): string
    {
        $start = $this->start_time ? $this->start_time->format('g:i A') : '';
        $end = $this->end_time ? $this->end_time->format('g:i A') : '';
        return "{$start} - {$end}";
    }

    /**
     * Get display label (Session Type + Day + Time Range)
     */
    public function getLabelAttribute(): string
    {
        $typeCode = $this->sessionType?->code ?? '';
        $prefix = $typeCode ? "[{$typeCode}] " : '';
        return "{$prefix}{$this->day} ({$this->time_range})";
    }

    /**
     * Scope to order by day and time
     */
    public function scopeOrdered($query)
    {
        if (config('database.default') === 'sqlite') {
            $sql = "CASE day 
                WHEN 'Sunday' THEN 1 
                WHEN 'Monday' THEN 2 
                WHEN 'Tuesday' THEN 3 
                WHEN 'Wednesday' THEN 4 
                WHEN 'Thursday' THEN 5 
                WHEN 'Friday' THEN 6 
                WHEN 'Saturday' THEN 7 
                ELSE 8 END";
            return $query->orderByRaw($sql)->orderBy('start_time');
        }

        return $query->orderByRaw("FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
            ->orderBy('start_time');
    }
}
