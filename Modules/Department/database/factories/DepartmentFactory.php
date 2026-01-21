<?php

namespace Modules\Department\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'faculty_id' => Faculty::factory(),
            'code' => fake()->unique()->regexify('[A-Z]{2,4}'),
            'name' => [
                'en' => fake()->randomElement(['Computer Science', 'Mathematics', 'Physics', 'Chemistry', 'Biology']),
                'ar' => fake()->randomElement(['علوم الحاسب', 'الرياضيات', 'الفيزياء', 'الكيمياء', 'الأحياء']),
            ],
            'status' => 'active',
        ];
    }

    /**
     * Configure for a specific faculty.
     */
    public function forFaculty(Faculty $faculty): static
    {
        return $this->state(fn(array $attributes) => [
            'faculty_id' => $faculty->id,
        ]);
    }

    /**
     * Create an inactive department.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
