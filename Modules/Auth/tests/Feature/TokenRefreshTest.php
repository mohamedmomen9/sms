<?php

namespace Modules\Auth\Tests\Feature;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Tests\Traits\InteractsWithJwt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Services\AuthService;

class TokenRefreshTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser, InteractsWithJwt;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpJwtTesting();
    }

    public function test_refresh_validates_required_token(): void
    {
        $response = $this->postJson('/api/v1/auth/refresh', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['refresh_token']);
    }

    public function test_refresh_fails_with_invalid_token(): void
    {
        $response = $this->postJson('/api/v1/auth/refresh', [
            'refresh_token' => 'invalid_token_here',
        ]);

        $response->assertStatus(401);
    }
}
