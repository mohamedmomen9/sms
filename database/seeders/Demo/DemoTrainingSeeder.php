<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Training\Models\TrainingOpportunity;

class DemoTrainingSeeder extends Seeder
{
    public function run(): void
    {
        $opportunities = [
            [
                'title' => 'Summer Internship 2026',
                'company' => 'Tech Corp',
                'description' => 'Software engineering internship',
                'location' => 'Cairo',
                'start_date' => '2026-07-01',
            ],
            [
                'title' => 'Medical Resident Program',
                'company' => 'City Hospital',
                'description' => 'Rotation program for med students',
                'location' => 'Alexandria',
                'start_date' => '2026-06-15',
            ],
        ];

        foreach ($opportunities as $op) {
            TrainingOpportunity::firstOrCreate(['title' => $op['title']], $op + [
                'is_active' => true,
                'deadline' => '2026-05-30',
            ]);
        }
    }
}
