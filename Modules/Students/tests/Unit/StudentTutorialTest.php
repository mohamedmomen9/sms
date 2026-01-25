<?php

namespace Modules\Students\Tests\Unit;

use Modules\Students\Models\StudentTutorial;
use Modules\Students\Models\Student;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTutorialTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_track_tutorial_completion()
    {
        $student = Student::factory()->create();

        $this->assertFalse(StudentTutorial::isCompleted($student->id, 'intro'));

        StudentTutorial::markCompleted($student->id, 'intro');

        $this->assertTrue(StudentTutorial::isCompleted($student->id, 'intro'));
    }
}
