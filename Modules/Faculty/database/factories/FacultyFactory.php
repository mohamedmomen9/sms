<?php

namespace Modules\Faculty\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Faculty\Models\Faculty;
use Modules\Campus\Models\Campus;

class FacultyFactory extends Factory
{
    protected $model = Faculty::class;

    public function definition(): array
    {
        return [
            'campus_id' => Campus::factory(),
            'code' => fake()->unique()->regexify('[A-Z]{2,3}'),
            'name' => [
                'en' => fake()->randomElement(['Engineering', 'Science', 'Arts', 'Business', 'Law', 'Medicine']),
                'ar' => fake()->randomElement(['الهندسة', 'العلوم', 'الآداب', 'الأعمال', 'القانون', 'الطب']),
            ],
        ];
    }

    /**
     * Configure for a specific campus.
     */
    public function forCampus(Campus $campus): static
    {
        return $this->state(fn(array $attributes) => [
            'campus_id' => $campus->id,
        ]);
    }
}
