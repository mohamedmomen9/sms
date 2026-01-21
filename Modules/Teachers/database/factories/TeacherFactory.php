<?php

namespace Modules\Teachers\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Teachers\Models\Teacher;
use Modules\Campus\Models\Campus;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => fake()->phoneNumber(),
            'qualification' => fake()->randomElement(['PhD', 'Masters', 'Bachelor']),
            'campus_id' => Campus::factory(),
        ];
    }

    /**
     * Create a teacher with PhD qualification.
     */
    public function phd(): static
    {
        return $this->state(fn(array $attributes) => [
            'qualification' => 'PhD',
        ]);
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
