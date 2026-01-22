<?php

namespace Modules\Services\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Services\Models\AppointmentSlot;
use Carbon\Carbon;

class AppointmentSlotFactory extends Factory
{
    protected $model = AppointmentSlot::class;

    public function definition(): array
    {
        $start = Carbon::createFromTime($this->faker->numberBetween(8, 16), 0, 0);
        $end = (clone $start)->addMinutes(30);

        return [
            'department_id' => \Modules\Services\Models\AppointmentDepartment::factory(),
            'day_of_week' => $this->faker->numberBetween(0, 6),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'label' => $start->format('g:i A') . ' - ' . $end->format('g:i A'),
            'is_available' => true,
            'max_capacity' => 1,
        ];
    }
}
