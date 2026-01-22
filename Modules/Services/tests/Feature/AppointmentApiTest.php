<?php

namespace Modules\Services\Tests\Feature;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Tests\Traits\InteractsWithJwt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Services\Models\AppointmentDepartment;
use Modules\Services\Models\AppointmentPurpose;
use Modules\Services\Models\AppointmentSlot;
use Carbon\Carbon;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser, InteractsWithJwt;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpJwtTesting();
        $this->seed(\Database\Seeders\Demo\DemoTestSeeder::class);
    }

    public function test_student_can_list_departments()
    {
        AppointmentDepartment::factory()->create(['name' => 'IT Support', 'is_active' => true]);

        $student = $this->createStudent();
        $token = $this->createMockJwtToken($student, 'student');

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/v1/appointments/departments');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'IT Support']);
    }

    public function test_student_can_book_appointment()
    {
        $student = $this->createStudent();
        $token = $this->createMockJwtToken($student, 'student');

        $department = AppointmentDepartment::factory()->create();
        $purpose = AppointmentPurpose::factory()->create(['department_id' => $department->id]);
        $slot = AppointmentSlot::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/v1/appointments/book', [
                'department_id' => $department->id,
                'purpose_id' => $purpose->id,
                'slot_id' => $slot->id,
                'date' => Carbon::tomorrow()->format('Y-m-d'),
                'phone' => '123456789',
                'notes' => 'Checking in',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('appointments', [
            'department_id' => $department->id,
            'student_id' => $student->student_id,
        ]);
    }
}
