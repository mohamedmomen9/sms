<?php

namespace Modules\Engagement\Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Engagement\Models\Survey;
use Modules\Campus\Models\Campus;

class SurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_active_filters_active_surveys(): void
    {
        Survey::create(['title' => 'Active', 'active' => true, 'url' => 'http://test.com']);
        Survey::create(['title' => 'Inactive', 'active' => false, 'url' => 'http://test.com']);

        $results = Survey::active()->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Active', $results->first()->title);
    }

    public function test_scope_for_user_filters_correctly(): void
    {
        $campus = Campus::factory()->create();
        $student = \Modules\Students\Models\Student::factory()->create(['campus_id' => $campus->id]);
        $teacher = \Modules\Teachers\Models\Teacher::factory()->create(['campus_id' => $campus->id]);

        // Targeted at Students, Same Campus
        Survey::create(['title' => 'Student Campus', 'active' => true, 'target_type' => 'STUDENT', 'campus_id' => $campus->id, 'url' => 'url']);
        // Targeted at All, Same Campus
        Survey::create(['title' => 'All Campus', 'active' => true, 'target_type' => 'ALL', 'campus_id' => $campus->id, 'url' => 'url']);
        // Targeted at Students, All Campuses
        Survey::create(['title' => 'Student All', 'active' => true, 'target_type' => 'STUDENT', 'campus_id' => null, 'url' => 'url']);
        // Targeted at Teachers (Should not see)
        Survey::create(['title' => 'Teacher Only', 'active' => true, 'target_type' => 'TEACHER', 'campus_id' => null, 'url' => 'url']);
        // Different Campus (Should not see)
        Survey::create(['title' => 'Other Campus', 'active' => true, 'target_type' => 'ALL', 'campus_id' => $campus->id + 1, 'url' => 'url']);

        $results = Survey::forUser($student)->get();

        $this->assertCount(3, $results);
        $this->assertTrue($results->contains('title', 'Student Campus'));
        $this->assertTrue($results->contains('title', 'All Campus'));
        $this->assertTrue($results->contains('title', 'Student All'));
        $this->assertFalse($results->contains('title', 'Teacher Only'));
        $this->assertFalse($results->contains('title', 'Other Campus'));
    }
}
