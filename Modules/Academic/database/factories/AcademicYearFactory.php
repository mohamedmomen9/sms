<?php

namespace Modules\Academic\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academic\Models\AcademicYear;

class AcademicYearFactory extends Factory
{
    protected $model = AcademicYear::class;

    public function definition(): array
    {
        $startYear = fake()->numberBetween(2020, 2025);
        $endYear = $startYear + 1;

        return [
            'name' => "{$startYear}-{$endYear}",
            'start_date' => "{$startYear}-09-01",
            'end_date' => "{$endYear}-06-30",
            'is_active' => false,
            'status' => 'active',
        ];
    }

    /**
     * Mark as active academic year.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Mark as inactive/closed.
     */
    public function closed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'closed',
            'is_active' => false,
        ]);
    }
}
