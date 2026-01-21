<?php

namespace Modules\Academic\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academic\Models\Term;
use Modules\Academic\Models\AcademicYear;

class TermFactory extends Factory
{
    protected $model = Term::class;

    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'name' => fake()->randomElement(['FALL', 'SPRING', 'SUMMER', 'WINTER']),
            'start_date' => now(),
            'end_date' => now()->addMonths(4),
            'is_active' => false,
        ];
    }

    /**
     * Mark as active term.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Create a FALL term.
     */
    public function fall(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'FALL',
            'start_date' => now()->month(9)->startOfMonth(),
            'end_date' => now()->month(12)->endOfMonth(),
        ]);
    }

    /**
     * Create a SPRING term.
     */
    public function spring(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'SPRING',
            'start_date' => now()->month(1)->startOfMonth(),
            'end_date' => now()->month(5)->endOfMonth(),
        ]);
    }

    /**
     * Configure for a specific academic year.
     */
    public function forAcademicYear(AcademicYear $academicYear): static
    {
        return $this->state(fn(array $attributes) => [
            'academic_year_id' => $academicYear->id,
        ]);
    }
}
