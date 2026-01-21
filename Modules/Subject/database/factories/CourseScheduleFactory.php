<?php

namespace Modules\Subject\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Subject\Models\CourseSchedule;
use Modules\Subject\Models\CourseOffering;
use Modules\Subject\Models\SessionType;
use Modules\Teachers\Models\Teacher;

class CourseScheduleFactory extends Factory
{
    protected $model = CourseSchedule::class;

    public function definition(): array
    {
        return [
            'course_offering_id' => CourseOffering::factory(),
            'session_type_id' => SessionType::factory(),
            'teacher_id' => Teacher::factory(),
            'day' => fake()->randomElement(CourseSchedule::DAYS),
            'start_time' => '09:00:00',
            'end_time' => '10:30:00',
        ];
    }
}
