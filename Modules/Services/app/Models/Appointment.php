<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class Appointment extends Model
{
    protected $fillable = [
        'student_id',
        'term_id',
        'department_id',
        'purpose_id',
        'slot_id',
        'appointment_date',
        'phone',
        'notes',
        'status',
        'language',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(AppointmentDepartment::class, 'department_id');
    }

    public function purpose(): BelongsTo
    {
        return $this->belongsTo(AppointmentPurpose::class, 'purpose_id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(AppointmentSlot::class, 'slot_id');
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('appointment_date', $date);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('appointment_date', '>=', now());
    }
}
