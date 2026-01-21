<?php

namespace Modules\Students\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Students\Models\CourseEnrollment;
use Modules\Students\Models\Student;
use Modules\Subject\Models\CourseOffering;

class CourseEnrollmentFactory extends Factory
{
    protected $model = CourseEnrollment::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_offering_id' => CourseOffering::factory(),
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ];
    }
}
