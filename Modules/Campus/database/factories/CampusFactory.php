<?php

namespace Modules\Campus\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Campus\Models\Campus;

class CampusFactory extends Factory
{
    protected $model = Campus::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->regexify('[A-Z]{3,4}'),
            'name' => [
                'en' => fake()->city() . ' Campus',
                'ar' => 'حرم ' . fake()->city(),
            ],
            'location' => fake()->address(),
            'address' => fake()->streetAddress(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'status' => 'active',
        ];
    }

    /**
     * Create an inactive campus.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
