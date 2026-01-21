<?php

namespace Modules\Services\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Services\Models\Appointment;
use Modules\Services\Models\AppointmentDepartment;
use Modules\Services\Models\AppointmentPurpose;
use Modules\Services\Models\AppointmentSlot;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'student_id' => fn() => Student::factory()->create()->student_id,
            'term_id' => Term::factory(),
            'department_id' => AppointmentDepartment::factory(),
            'purpose_id' => AppointmentPurpose::factory(),
            'slot_id' => AppointmentSlot::factory(),
            'appointment_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->sentence(),
            'status' => 'booked', // booked, completed, cancelled
            'language' => 'en',
        ];
    }
}
