<?php

namespace Modules\Services\Tests\Unit\Services;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Services\Services\ServiceRequestService;
use Modules\Services\Models\ServiceRequest;
use Modules\Services\Models\ServiceType;
use Modules\Academic\Models\Term;
use Modules\Students\Models\Student;

class ServiceRequestServiceTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser;

    protected ServiceRequestService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ServiceRequestService();
        $this->seed(\Database\Seeders\Demo\DemoTestSeeder::class);
    }

    public function test_submit_creates_request_with_correct_price()
    {
        $student = $this->createStudent();
        $term = Term::first();
        $type = ServiceType::factory()->create(['price' => 50.00, 'is_active' => true]);

        $request = $this->service->submit($student, $term, $type->id, 'My Notes');

        $this->assertInstanceOf(ServiceRequest::class, $request);
        $this->assertEquals(50.00, $request->payment_amount);
        $this->assertEquals('pending', $request->status);
        $this->assertDatabaseHas('service_requests', [
            'student_id' => $student->student_id,
            'service_type_id' => $type->id,
        ]);
    }

    public function test_get_available_services_returns_active_types()
    {
        ServiceType::factory()->create(['is_active' => true, 'is_mobile_visible' => true, 'name' => 'Visible']);
        ServiceType::factory()->create(['is_active' => false, 'name' => 'Inactive']);
        ServiceType::factory()->create(['is_active' => true, 'is_mobile_visible' => false, 'name' => 'Hidden']);

        $term = Term::first();
        $services = $this->service->getAvailableServices($term);

        $this->assertCount(1, $services);
        $this->assertEquals('Visible', $services->first()['name']);
    }

    public function test_update_status_handles_delivered()
    {
        $request = ServiceRequest::factory()->create(['status' => 'pending']);

        $this->service->updateStatus($request, 'delivered');

        $request->refresh();
        $this->assertEquals('delivered', $request->status);
        $this->assertNotNull($request->delivered_at);
    }
}
