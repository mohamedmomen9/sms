<?php

namespace Modules\Services\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Services\Models\AppointmentDepartment;

class AppointmentDepartmentFactory extends Factory
{
    protected $model = AppointmentDepartment::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true) . ' Department',
            'is_active' => true,
        ];
    }
}
