<?php

namespace Modules\Services\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Services\Models\AppointmentPurpose;
use Modules\Services\Models\AppointmentDepartment;

class AppointmentPurposeFactory extends Factory
{
    protected $model = AppointmentPurpose::class;

    public function definition(): array
    {
        return [
            'department_id' => AppointmentDepartment::factory(),
            'name' => $this->faker->words(3, true),
            'is_active' => true,
        ];
    }
}
