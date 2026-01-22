<?php

namespace Modules\Services\Tests\Feature;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Tests\Traits\InteractsWithJwt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Services\Models\ServiceType;

class ServiceRequestApiTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser, InteractsWithJwt;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpJwtTesting();
        $this->seed(\Database\Seeders\Demo\DemoTestSeeder::class);
    }

    public function test_student_can_list_available_services()
    {
        ServiceType::factory()->create(['name' => 'Certificate Request', 'is_active' => true, 'is_mobile_visible' => true]);

        $student = $this->createStudent();
        $token = $this->createMockJwtToken($student, 'student');

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/v1/services');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Certificate Request']);
    }

    public function test_student_can_submit_service_request()
    {
        $student = $this->createStudent();
        $token = $this->createMockJwtToken($student, 'student');

        $type = ServiceType::factory()->create(['is_active' => true]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/v1/services/request', [
                'service_type_id' => $type->id,
                'notes' => 'Please hurry',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('service_requests', [
            'service_type_id' => $type->id,
            'student_id' => $student->student_id,
        ]);
    }
}
