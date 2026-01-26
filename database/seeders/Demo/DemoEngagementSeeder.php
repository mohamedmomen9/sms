<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Engagement\Models\Survey;
use Modules\Campus\Models\Campus;

class DemoEngagementSeeder extends Seeder
{
    public function run(): void
    {
        $campus = Campus::first();
        $campusId = $campus ? $campus->id : null;

        $surveys = [
            [
                'title' => 'Student Satisfaction Survey 2026',
                'url' => 'https://forms.example.com/satisfaction-2026',
                'active' => true,
                'target_type' => 'STUDENT',
                'campus_id' => $campusId,
            ],
            [
                'title' => 'Campus Facilities Feedback',
                'url' => 'https://forms.example.com/facilities',
                'active' => true,
                'target_type' => 'ALL',
                'campus_id' => null,
            ],
            [
                'title' => 'Instructor Evaluation - Spring Term',
                'url' => 'https://forms.example.com/instructor-eval',
                'active' => true,
                'target_type' => 'STUDENT',
                'campus_id' => null,
            ],
        ];

        foreach ($surveys as $data) {
            Survey::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }
}
