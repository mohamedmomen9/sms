<?php

namespace Modules\Payment\Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Payment\Services\PaymentService;
use Modules\Payment\Models\PaymentRegistration;
use Modules\Services\Models\ServiceRequest;
use Modules\Services\Models\ServiceType;
use Modules\Academic\Models\Term;
use Carbon\Carbon;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser;

    protected PaymentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PaymentService();
        $this->seed(\Database\Seeders\TestDatabaseSeeder::class);
    }

    public function test_create_registration_stores_correct_data()
    {
        $student = $this->createStudent();
        // Create Request (manually to ensure valid FKs)
        $term = Term::first();
        $type = ServiceType::factory()->create(['price' => 100.00]);

        $request = ServiceRequest::create([
            'student_id' => $student->student_id,
            'term_id' => $term->id,
            'service_type_id' => $type->id,
            'payment_amount' => 100.00,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $registration = $this->service->createRegistration($student, $request, 'mobile_wallet');

        $this->assertInstanceOf(PaymentRegistration::class, $registration);
        $this->assertEquals(100.00, $registration->amount);
        $this->assertEquals('mobile_wallet', $registration->payment_method);
        $this->assertEquals('pending', $registration->callback_status);
        $this->assertEquals($request->id, $registration->service_request_id);
    }

    public function test_process_callback_success_updates_status_and_request()
    {
        $registration = PaymentRegistration::factory()->create([
            'callback_status' => 'pending',
            'amount' => 50.00,
        ]);

        // Ensure associated request is pending
        $registration->serviceRequest->update(['payment_status' => 'pending']);

        $success = $this->service->processCallback(
            $registration,
            'success',
            'TXN-12345',
            ['provider' => 'stripe']
        );

        $this->assertTrue($success);

        $registration->refresh();
        $this->assertEquals('success', $registration->callback_status);
        $this->assertEquals('TXN-12345', $registration->transaction_id);
        $this->assertNotNull($registration->payment_date);

        // Check Update on Service Request
        $this->assertEquals('paid', $registration->serviceRequest->fresh()->payment_status);
    }

    public function test_process_callback_failure_does_not_mark_paid()
    {
        $registration = PaymentRegistration::factory()->create([
            'callback_status' => 'pending',
        ]);

        $success = $this->service->processCallback(
            $registration,
            'failed',
            null,
            ['error' => 'insufficient_funds']
        );

        $this->assertTrue($success); // Update successful, even if payment failed

        $registration->refresh();
        $this->assertEquals('failed', $registration->callback_status);
        $this->assertNull($registration->payment_date);

        // Service Request should remains pending
        $this->assertEquals('pending', $registration->serviceRequest->fresh()->payment_status);
    }

    public function test_find_by_transaction_id()
    {
        PaymentRegistration::factory()->create(['transaction_id' => 'TXN-A', 'amount' => 10]);
        PaymentRegistration::factory()->create(['transaction_id' => 'TXN-B', 'amount' => 20]);

        $found = $this->service->findByTransactionId('TXN-A');

        $this->assertNotNull($found);
        $this->assertEquals(10, $found->amount);

        $notFound = $this->service->findByTransactionId('TXN-Z');
        $this->assertNull($notFound);
    }
}
