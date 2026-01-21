<?php

namespace Modules\Services\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Models\Term;
use Modules\Services\Models\Appointment;
use Modules\Services\Models\AppointmentDepartment;
use Modules\Services\Services\AppointmentService;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService
    ) {}

    /**
     * GET /api/v1/appointments/departments
     */
    public function departments(): JsonResponse
    {
        $departments = AppointmentDepartment::active()
            ->with('purposes')
            ->get();

        return ApiResponse::success($departments);
    }

    /**
     * GET /api/v1/appointments/slots
     */
    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'department_id' => 'required|exists:appointment_departments,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $student = $request->user();
        $date = Carbon::parse($request->date);

        $slots = $this->appointmentService->getAvailableSlots(
            $student,
            $request->department_id,
            $date
        );

        return ApiResponse::success($slots);
    }

    /**
     * POST /api/v1/appointments/book
     */
    public function book(Request $request): JsonResponse
    {
        $request->validate([
            'department_id' => 'required|exists:appointment_departments,id',
            'purpose_id' => 'required|exists:appointment_purposes,id',
            'slot_id' => 'required|exists:appointment_slots,id',
            'date' => 'required|date|after_or_equal:today',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $student = $request->user();
        $term = Term::where('is_active', true)->first();

        if (!$term) {
            return ApiResponse::notFound('No active term found');
        }

        $date = Carbon::parse($request->date);

        if (!$this->appointmentService->isSlotAvailable(
            $request->department_id,
            $date,
            $request->slot_id
        )) {
            return ApiResponse::error('Slot is no longer available', 409);
        }

        $appointment = $this->appointmentService->book(
            $student,
            $term,
            $request->department_id,
            $request->purpose_id,
            $request->slot_id,
            $date,
            $request->phone,
            $request->notes
        );

        return ApiResponse::created(
            $appointment->load(['department', 'purpose', 'slot']),
            'Appointment booked successfully'
        );
    }

    /**
     * GET /api/v1/appointments/my
     */
    public function myAppointments(Request $request): JsonResponse
    {
        $student = $request->user();
        $term = Term::where('is_active', true)->first();

        $appointments = $this->appointmentService->getStudentAppointments($student, $term);

        return ApiResponse::success($appointments);
    }

    /**
     * DELETE /api/v1/appointments/{id}
     */
    public function cancel(int $id, Request $request): JsonResponse
    {
        $student = $request->user();
        $appointment = Appointment::where('id', $id)
            ->where('student_id', $student->student_id)
            ->where('status', 'booked')
            ->first();

        if (!$appointment) {
            return ApiResponse::notFound('Appointment not found');
        }

        $this->appointmentService->cancel($appointment);

        return ApiResponse::success(null, 'Appointment cancelled');
    }
}
