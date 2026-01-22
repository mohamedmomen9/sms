<?php

namespace Modules\Services\Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Services\Services\AppointmentService;
use Modules\Services\Models\Appointment;
use Modules\Services\Models\AppointmentDepartment;
use Modules\Services\Models\AppointmentPurpose;
use Modules\Services\Models\AppointmentSlot;
use Modules\Academic\Models\Term;
use Modules\Students\Models\Student;
use Carbon\Carbon;

class AppointmentServiceTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser;

    protected AppointmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AppointmentService();
        $this->seed(\Database\Seeders\Demo\DemoTestSeeder::class);
    }

    public function test_get_available_slots_respects_capacity()
    {
        $student = $this->createStudent();
        $term = Term::first();
        $department = AppointmentDepartment::factory()->create();
        $slot = AppointmentSlot::factory()->create(['start_time' => '10:00:00']);

        $date = Carbon::tomorrow();

        // Capacity is 2. Book 1 slot.
        Appointment::factory()->create([
            'department_id' => $department->id,
            'slot_id' => $slot->id,
            'appointment_date' => $date,
            'status' => 'booked',
        ]);

        $result = $this->service->getAvailableSlots($student, $department->id, $date, 2);

        $this->assertCount(1, $result['available_slots']);
        $this->assertEquals(1, $result['available_slots'][0]['available_count']);

        // Book another slot (FULL)
        Appointment::factory()->create([
            'department_id' => $department->id,
            'slot_id' => $slot->id,
            'appointment_date' => $date,
            'status' => 'booked',
        ]);

        $resultFull = $this->service->getAvailableSlots($student, $department->id, $date, 2);

        $this->assertCount(0, $resultFull['available_slots']);
    }

    public function test_book_creates_appointment()
    {
        $student = $this->createStudent();
        $term = Term::first();
        $department = AppointmentDepartment::factory()->create();
        $purpose = AppointmentPurpose::factory()->create();
        $slot = AppointmentSlot::factory()->create();

        $date = Carbon::tomorrow();

        $appointment = $this->service->book(
            $student,
            $term,
            $department->id,
            $purpose->id,
            $slot->id,
            $date,
            '123456789',
            'Test Notes'
        );

        $this->assertInstanceOf(Appointment::class, $appointment);
        $this->assertEquals('booked', $appointment->status);
        $this->assertEquals('123456789', $appointment->phone);
        $this->assertDatabaseHas('appointments', [
            'department_id' => $department->id,
            'student_id' => $student->student_id,
        ]);
    }

    public function test_cancel_updates_status()
    {
        $appointment = Appointment::factory()->create(['status' => 'booked']);

        $this->service->cancel($appointment);

        $this->assertEquals('cancelled', $appointment->fresh()->status);
    }
}
