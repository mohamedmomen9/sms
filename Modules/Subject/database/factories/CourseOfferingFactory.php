<?php

namespace Modules\Subject\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Subject\Models\CourseOffering;
use Modules\Subject\Models\Subject;
use Modules\Academic\Models\Term;
use Modules\Campus\Models\Room;

class CourseOfferingFactory extends Factory
{
    protected $model = CourseOffering::class;

    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),
            'term_id' => Term::factory(),
            'section_number' => fake()->randomElement(['A', 'B', 'C', '1', '2']),
            'capacity' => fake()->numberBetween(20, 100),
            'room_id' => null, // Making room optional for basic tests
            'schedule_json' => [],
        ];
    }
}
