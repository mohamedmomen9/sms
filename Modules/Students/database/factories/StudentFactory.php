<?php

namespace Modules\Students\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Students\Models\Student;
use Modules\Campus\Models\Campus;
use Modules\Faculty\Models\Faculty;
use Modules\Department\Models\Department;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'student_id' => fake()->unique()->numerify('STU#####'),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'campus_id' => Campus::factory(),
            'school_id' => Faculty::factory(),
            'department_id' => Department::factory(),
        ];
    }

    /**
     * Configure the model for a specific campus.
     */
    public function forCampus(Campus $campus): static
    {
        return $this->state(fn(array $attributes) => [
            'campus_id' => $campus->id,
        ]);
    }

    /**
     * Configure the model for a specific department.
     */
    public function forDepartment(Department $department): static
    {
        return $this->state(fn(array $attributes) => [
            'department_id' => $department->id,
            'school_id' => $department->faculty_id,
        ]);
    }
}
