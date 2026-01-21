<?php

namespace Modules\Services\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Services\Models\Appointment;
use Modules\Services\Models\AppointmentDepartment;
use Modules\Services\Models\AppointmentSlot;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class AppointmentService
{
    /**
     * Get available appointment slots for a date and department.
     */
    public function getAvailableSlots(
        Student $student,
        int $departmentId,
        Carbon $date,
        int $capacity = 10
    ): array {
        $bookedSlotIds = Appointment::query()
            ->where('student_id', $student->student_id)
            ->where('department_id', $departmentId)
            ->whereDate('appointment_date', $date)
            ->where('status', 'booked')
            ->pluck('slot_id')
            ->toArray();

        $availableSlots = AppointmentSlot::query()
            ->where('is_available', true)
            ->withCount([
                'appointments' => fn($q) => $q
                    ->where('department_id', $departmentId)
                    ->whereDate('appointment_date', $date)
                    ->where('status', 'booked')
            ])
            ->get()
            ->filter(fn($slot) => $slot->appointments_count < $capacity)
            ->map(fn($slot) => [
                'id' => $slot->id,
                'time' => $slot->label,
                'start_time' => $slot->start_time->format('H:i'),
                'end_time' => $slot->end_time->format('H:i'),
                'available_count' => $capacity - $slot->appointments_count,
            ]);

        return [
            'booked_slot_ids' => $bookedSlotIds,
            'available_slots' => $availableSlots->values()->toArray(),
        ];
    }

    /**
     * Get unavailable dates (vacations + closed days).
     */
    public function getUnavailableDates(int $departmentId): array
    {
        // TODO: Implement based on vacation storage
        return [
            'vacations' => [],
            'closed_days' => [],
        ];
    }

    /**
     * Check if a slot is available for booking.
     */
    public function isSlotAvailable(
        int $departmentId,
        Carbon $date,
        int $slotId,
        int $capacity = 10
    ): bool {
        $currentBookings = Appointment::query()
            ->where('department_id', $departmentId)
            ->whereDate('appointment_date', $date)
            ->where('slot_id', $slotId)
            ->where('status', 'booked')
            ->count();

        return $currentBookings < $capacity;
    }

    /**
     * Book a new appointment.
     */
    public function book(
        Student $student,
        Term $term,
        int $departmentId,
        int $purposeId,
        int $slotId,
        Carbon $date,
        string $phone,
        ?string $notes = null,
        string $language = 'ar'
    ): Appointment {
        return Appointment::create([
            'student_id' => $student->student_id,
            'term_id' => $term->id,
            'department_id' => $departmentId,
            'purpose_id' => $purposeId,
            'slot_id' => $slotId,
            'appointment_date' => $date,
            'phone' => $phone,
            'notes' => $notes,
            'language' => $language,
            'status' => 'booked',
        ]);
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Appointment $appointment): bool
    {
        return $appointment->update(['status' => 'cancelled']);
    }

    /**
     * Get student's appointments.
     */
    public function getStudentAppointments(Student $student, ?Term $term = null): Collection
    {
        return Appointment::query()
            ->where('student_id', $student->student_id)
            ->when($term, fn($q) => $q->where('term_id', $term->id))
            ->with(['department', 'purpose', 'slot'])
            ->orderByDesc('appointment_date')
            ->get();
    }
}
