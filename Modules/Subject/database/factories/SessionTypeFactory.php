<?php

namespace Modules\Subject\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Subject\Models\SessionType;

class SessionTypeFactory extends Factory
{
    protected $model = SessionType::class;

    public function definition(): array
    {
        // For testing purposes, we prefer using existing standard types if possible to avoid unique constraint violations
        // But factories usually create new. We'll use random unique codes but since we have a fixed list,
        // we might run out or collide.
        // Better strategy: Return one of the codes, but we rely on tests to use firstOrCreate 
        // if they run multiple times.
        // OR: generate a truly random code that is NOT in the standard list for non-standard types.

        $code = fake()->unique()->lexify('????'); // Random 4 letters

        return [
            'code' => $code,
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }

    /**
     * State for a standard Lecture session
     */
    public function lecture(): static
    {
        return $this->state(fn(array $attributes) => [
            'code' => 'LECT',
            'name' => 'Lecture',
        ]);
    }

    /**
     * State for a standard Lab session
     */
    public function lab(): static
    {
        return $this->state(fn(array $attributes) => [
            'code' => 'LAB',
            'name' => 'Laboratory',
        ]);
    }
}
