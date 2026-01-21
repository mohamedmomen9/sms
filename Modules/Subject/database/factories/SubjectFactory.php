<?php

namespace Modules\Subject\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Subject\Models\Subject;
use Modules\Faculty\Models\Faculty;
use Modules\Department\Models\Department;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            'name' => [
                'en' => fake()->unique()->word() . ' ' . fake()->randomElement(['101', '102', '201', '300']),
                'ar' => fake()->unique()->word() . ' ar',
            ],
            'code' => fake()->unique()->bothify('SUB###'),
            'faculty_id' => Faculty::factory(),
            'department_id' => Department::factory(),
        ];
    }
}
