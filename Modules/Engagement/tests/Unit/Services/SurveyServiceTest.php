<?php

namespace Modules\Engagement\Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Engagement\Models\Survey;
use Modules\Engagement\Models\SurveyLog;
use Modules\Engagement\Services\SurveyService;

class SurveyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SurveyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SurveyService();
    }

    public function test_log_participation_creates_log(): void
    {
        $survey = Survey::create(['title' => 'Test', 'url' => 'url', 'target_type' => 'ALL']);
        $student = \Modules\Students\Models\Student::factory()->create();

        $log = $this->service->logParticipation($survey->id, $student);

        $this->assertDatabaseHas('survey_logs', [
            'survey_id' => $survey->id,
            'participant_id' => $student->id,
            'participant_type' => 'STUDENT',
            'status' => true
        ]);
    }

    public function test_log_participation_prevents_duplicates(): void
    {
        $survey = Survey::create(['title' => 'Test', 'url' => 'url', 'target_type' => 'ALL']);
        $student = \Modules\Students\Models\Student::factory()->create();

        $log1 = $this->service->logParticipation($survey->id, $student);
        $log2 = $this->service->logParticipation($survey->id, $student);

        $this->assertEquals($log1->id, $log2->id);
        $this->assertCount(1, SurveyLog::all());
    }

    public function test_has_participated_checks_log(): void
    {
        $survey = Survey::create(['title' => 'Test', 'url' => 'url', 'target_type' => 'ALL']);
        $student = \Modules\Students\Models\Student::factory()->create();

        $this->assertFalse($this->service->hasParticipated($survey->id, $student));

        $this->service->logParticipation($survey->id, $student);

        $this->assertTrue($this->service->hasParticipated($survey->id, $student));
    }

    public function test_get_participation_stats(): void
    {
        $survey = Survey::create(['title' => 'Test', 'url' => 'url']);
        SurveyLog::create(['survey_id' => $survey->id, 'participant_type' => 'STUDENT', 'participant_id' => 1, 'status' => true]);
        SurveyLog::create(['survey_id' => $survey->id, 'participant_type' => 'TEACHER', 'participant_id' => 1, 'status' => true]);

        $stats = $this->service->getParticipationStats($survey->id);

        $this->assertEquals(2, $stats['total']);
        $this->assertEquals(1, $stats['by_type']['student']);
        $this->assertEquals(1, $stats['by_type']['teacher']);
    }
}
