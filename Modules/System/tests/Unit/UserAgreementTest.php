<?php

namespace Modules\System\Tests\Unit;

use Modules\System\Models\UserAgreement;
use Modules\Students\Models\Student;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAgreementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_accept_agreement()
    {
        $student = Student::factory()->create();

        $this->assertFalse(UserAgreement::hasAccepted($student, 'terms'));

        UserAgreement::accept($student, 'terms');

        $this->assertTrue(UserAgreement::hasAccepted($student, 'terms'));
    }
}
