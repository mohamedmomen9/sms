<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Tests\Traits\InteractsWithJwt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser, InteractsWithJwt;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpJwtTesting();
    }

    public function test_logout_validates_required_token(): void
    {
        $response = $this->postJson('/api/v1/auth/logout', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['refresh_token']);
    }

    public function test_logout_accepts_any_token_format(): void
    {
        // Logout should gracefully handle any token
        $response = $this->postJson('/api/v1/auth/logout', [
            'refresh_token' => 'any_token_value',
        ]);

        // Even invalid tokens should return success (idempotent logout)
        $response->assertStatus(200);
    }
}
