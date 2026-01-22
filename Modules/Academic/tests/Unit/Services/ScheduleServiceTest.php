<?php

namespace Modules\Academic\Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Academic\Services\ScheduleService;
use Modules\Subject\Models\CourseOffering;
use Modules\Subject\Models\CourseSchedule;
use Modules\Subject\Models\SessionType;
use Modules\Students\Models\CourseEnrollment;
use Modules\Academic\Models\Term;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;
use Carbon\Carbon;

class ScheduleServiceTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser;

    protected ScheduleService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ScheduleService();
        $this->seed(\Database\Seeders\Demo\DemoTestSeeder::class);
    }

    public function test_get_student_schedule_returns_correct_items(): void
    {
        // 1. Setup Data
        $term = Term::first();
        $student = $this->createStudent();

        // Create 2 courses
        $offering1 = CourseOffering::factory()->create(['term_id' => $term->id]);
        $offering2 = CourseOffering::factory()->create(['term_id' => $term->id]);

        // Enroll student in both
        CourseEnrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $offering1->id,
        ]);
        CourseEnrollment::factory()->create([
            'student_id' => $student->id,
            'course_offering_id' => $offering2->id,
        ]);

        // Add schedule items
        CourseSchedule::factory()->create([
            'course_offering_id' => $offering1->id,
            'day' => 'Monday',
            'start_time' => '09:00:00'
        ]);
        CourseSchedule::factory()->create([
            'course_offering_id' => $offering2->id,
            'day' => 'Tuesday',
            'start_time' => '10:00:00'
        ]);

        // 2. Execute
        $schedule = $this->service->getStudentSchedule($student, $term);

        // 3. Verify
        $this->assertEquals(2, $schedule->count());
        $this->assertEquals('Monday', $schedule->first()['day']);
        $this->assertEquals($offering1->subject->name, $schedule->first()['subject_name']);
    }

    public function test_get_student_schedule_returns_empty_when_not_enrolled(): void
    {
        $term = Term::first();
        $student = $this->createStudent();

        // Create course but don't enroll
        CourseOffering::factory()->create(['term_id' => $term->id]);

        $schedule = $this->service->getStudentSchedule($student, $term);

        $this->assertTrue($schedule->isEmpty());
    }

    public function test_group_schedule_by_day_groups_correctly(): void
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

        CourseSchedule::factory()->create([
            'course_offering_id' => $offering->id,
            'day' => 'Monday',
            'start_time' => '10:00:00'
        ]);

        CourseSchedule::factory()->create([
            'course_offering_id' => $offering->id,
            'day' => 'Wednesday',
            'start_time' => '09:00:00'
        ]);

        $schedule = $this->service->getStudentSchedule($student, $term);
        $grouped = $this->service->groupScheduleByDay($schedule);

        // Should be 2 groups: Monday (2 classes) and Wednesday (1 class)
        $this->assertEquals(2, $grouped->count());
        $this->assertEquals('Monday', $grouped->first()['day']);
        $this->assertEquals(2, count($grouped->first()['classes']));
        $this->assertEquals('Wednesday', $grouped->last()['day']);
    }

    public function test_get_teacher_schedule_returns_assigned_items(): void
    {
        $term = Term::first();
        $teacher = $this->createTeacher();

        $offering = CourseOffering::factory()->create(['term_id' => $term->id]);

        // Assign teacher to offering
        $offering->teachers()->attach($teacher->id, ['is_primary' => true]);

        // Schedule where teacher is explicitly assigned
        CourseSchedule::factory()->create([
            'course_offering_id' => $offering->id,
            'teacher_id' => $teacher->id,
            'day' => 'Monday'
        ]);

        // Schedule where teacher is null (implies primary instructor)
        CourseSchedule::factory()->create([
            'course_offering_id' => $offering->id,
            'teacher_id' => null,
            'day' => 'Tuesday'
        ]);

        $schedule = $this->service->getTeacherSchedule($teacher, $term);

        $this->assertEquals(2, $schedule->count());
    }
}
