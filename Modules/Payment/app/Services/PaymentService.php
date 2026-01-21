<?php

namespace Modules\Payment\Services;

use Modules\Payment\Models\PaymentRegistration;
use Modules\Services\Models\ServiceRequest;
use Modules\Students\Models\Student;

class PaymentService
{
    /**
     * Create payment registration.
     */
    public function createRegistration(
        Student $student,
        ServiceRequest $request,
        string $paymentMethod = 'online'
    ): PaymentRegistration {
        return PaymentRegistration::create([
            'student_id' => $student->student_id,
            'service_request_id' => $request->id,
            'amount' => $request->payment_amount,
            'payment_method' => $paymentMethod,
            'callback_status' => 'pending',
        ]);
    }

    /**
     * Process payment callback.
     */
    public function processCallback(
        PaymentRegistration $registration,
        string $status,
        ?string $transactionId = null,
        ?array $callbackData = null
    ): bool {
        $updated = $registration->update([
            'callback_status' => $status,
            'transaction_id' => $transactionId,
            'callback_data' => $callbackData,
            'payment_date' => $status === 'success' ? now() : null,
        ]);

        if ($status === 'success') {
            $registration->serviceRequest->update(['payment_status' => 'paid']);
        }

        return $updated;
    }

    /**
     * Get payment by transaction ID.
     */
    public function findByTransactionId(string $transactionId): ?PaymentRegistration
    {
        return PaymentRegistration::where('transaction_id', $transactionId)->first();
    }
}
