<?php

namespace Modules\Admissions\Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Admissions\Models\Applicant;
use Modules\Admissions\Services\ApplicantService;

class ApplicantTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_applicant(): void
    {
        $applicant = Applicant::create([
            'name' => 'New Applicant',
            'email' => 'app@test.com',
            'phone' => '5551234',
            'password' => 'secret',
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('applicants', ['email' => 'app@test.com']);
        $this->assertEquals('pending', $applicant->status);
    }

    public function test_service_can_change_status(): void
    {
        $applicant = Applicant::create([
            'name' => 'A',
            'email' => 'a@a.com',
            'phone' => '1',
            'password' => 'p'
        ]);

        $service = new ApplicantService();
        $updated = $service->changeStatus($applicant->id, 'accepted');

        $this->assertEquals('accepted', $updated->status);
    }
}
