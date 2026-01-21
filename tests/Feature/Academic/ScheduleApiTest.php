<?php

namespace Tests\Feature\Academic;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Tests\Traits\InteractsWithJwt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Academic\Models\Term;
use Modules\Subject\Models\CourseOffering;
use Modules\Subject\Models\CourseSchedule;
use Modules\Students\Models\CourseEnrollment;

class ScheduleApiTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser, InteractsWithJwt;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpJwtTesting();
        $this->seed(\Database\Seeders\TestDatabaseSeeder::class);
    }

    public function test_student_can_fetch_their_schedule(): void
    {
        $term = Term::first();
        $student = $this->createStudent();

        $offering = CourseOffering::factory()->create(['term_id' => $term->id]);

        CourseEnrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $offering->id,
        ]);

        CourseSchedule::factory()->create([
            'course_offering_id' => $offering->id,
            'day' => 'Monday',
            'start_time' => '09:00:00'
        ]);

        // Login as student to get token (or just mock actingAs if I didn't rely on JWT middleware mocking)
        // Since InteractsWithJwt provides `actingAsStudent`, I'll use that.

        $token = $this->createMockJwtToken($student, 'student');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/student/schedule');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }

    public function test_student_can_fetch_today_schedule(): void
    {
        $student = $this->createStudent();
        $token = $this->createMockJwtToken($student, 'student');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/student/schedule/today');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }

    public function test_teacher_can_fetch_their_schedule(): void
    {
        $teacher = $this->createTeacher();
        $token = $this->createMockJwtToken($teacher, 'teacher');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/teacher/schedule');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }

    public function test_schedule_grouping_parameter(): void
    {
        $term = Term::first();
        $student = $this->createStudent();

        $offering = CourseOffering::factory()->create(['term_id' => $term->id]);
        CourseEnrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $offering->id,
        ]);
        CourseSchedule::factory()->create([
            'course_offering_id' => $offering->id,
            'day' => 'Monday'
        ]);

        $token = $this->createMockJwtToken($student, 'student');

        // Test groupBy=day
        $responseDay = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/v1/student/schedule?group_by=day');

        $responseDay->assertStatus(200);

        // Test groupBy=course
        $responseCourse = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/v1/student/schedule?group_by=course');

        $responseCourse->assertStatus(200);
    }
}
