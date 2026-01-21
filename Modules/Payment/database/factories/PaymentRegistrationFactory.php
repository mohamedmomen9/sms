<?php

namespace Modules\Payment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Payment\Models\PaymentRegistration;
use Modules\Students\Models\Student;
use Modules\Services\Models\ServiceRequest;

class PaymentRegistrationFactory extends Factory
{
    protected $model = PaymentRegistration::class;

    public function definition(): array
    {
        return [
            'student_id' => fn() => Student::factory()->create()->student_id,
            'service_request_id' => ServiceRequest::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'payment_method' => 'online',
            'transaction_id' => null,
            'callback_status' => 'pending', // pending, success, failed
            'callback_data' => null,
            'payment_date' => null,
        ];
    }
}
