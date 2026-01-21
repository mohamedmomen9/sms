<?php

namespace Modules\Services\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Services\Models\ServiceRequest;
use Modules\Services\Models\ServiceType;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class ServiceRequestFactory extends Factory
{
    protected $model = ServiceRequest::class;

    public function definition(): array
    {
        return [
            'student_id' => fn() => Student::factory()->create()->student_id,
            'term_id' => Term::factory(), // Or retrieve existing
            'service_type_id' => ServiceType::factory(),
            'notes' => $this->faker->sentence(),
            'payment_amount' => $this->faker->randomFloat(2, 10, 100),
            'payment_status' => 'pending', // pending, paid, failed
            'status' => 'pending', // pending, processing, completed, rejected
            'shipping_required' => false,
            'directed_to' => null,
            'delivered_at' => null,
        ];
    }
}
