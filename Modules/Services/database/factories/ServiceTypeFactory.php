<?php

namespace Modules\Services\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Services\Models\ServiceType;

class ServiceTypeFactory extends Factory
{
    protected $model = ServiceType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'code' => $this->faker->unique()->bothify('SRV-####'),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'duration_days' => $this->faker->numberBetween(1, 30),
            'requires_shipping' => $this->faker->boolean(20),
            'is_mobile_visible' => true,
            'is_active' => true,
        ];
    }
}
