<?php

namespace Modules\Family\Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Family\Models\Guardian;
use Modules\Family\Services\ParentAuthService;
use Modules\Students\Models\Student;

class GuardianTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_guardian_linked_to_student(): void
    {
        // Require student factory to be working or create student manually
        // Assuming Student factory exists from previous stages
        // But if not, we can assume create works

        $student = Student::create(['name' => 'Kid', 'email' => 'k@test.com', 'password' => 'x']);

        $guardian = Guardian::create([
            'name' => 'Parent',
            'phone' => '123456',
            'password' => 'pass',
            'student_id' => $student->id
        ]);

        $this->assertEquals($student->id, $guardian->student->id);
        $this->assertTrue($student->guardians->contains($guardian));
    }

    public function test_otp_generation(): void
    {
        $student = Student::create(['name' => 'Kid', 'email' => 'k@test.com', 'password' => 'x']);
        $guardian = Guardian::create(['name' => 'P', 'phone' => '999', 'student_id' => $student->id, 'password' => 'pass']);

        $service = new ParentAuthService();
        $otp = $service->generateOtp($guardian->id);

        $this->assertNotEmpty($otp);
        $this->assertDatabaseHas('parent_verifications', ['parent_id' => $guardian->id]);
    }
}
