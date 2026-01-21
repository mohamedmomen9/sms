<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Config;

/**
 * Helper trait for JWT authentication in tests.
 * Mocks password decryption for testing without RSA keys.
 */
trait InteractsWithJwt
{
    /**
     * Set up JWT testing environment.
     * Call this in setUp() when testing JWT-authenticated endpoints.
     */
    protected function setUpJwtTesting(): void
    {
        // Configure JWT for testing
        Config::set('jwt-auth.access_token_ttl', 60);
        Config::set('jwt-auth.refresh_token_ttl', 7);
    }

    /**
     * Get login credentials for API testing.
     * Uses plain password since we mock decryption in tests.
     */
    protected function getTestLoginPayload(string $email, string $role = 'student', string $deviceName = 'test_device'): array
    {
        return [
            'username' => $email,
            'password' => $this->encryptPasswordForTest('password'),
            'device_name' => $deviceName,
            'role' => $role,
        ];
    }

    /**
     * Mock password encryption for testing.
     * In production, passwords are RSA-encrypted by the client.
     * For tests, we either mock the decryption or use plain passwords.
     */
    protected function encryptPasswordForTest(string $password): string
    {
        // Return base64-encoded plain password for test environment
        // The AuthService should be mocked to accept this in tests
        return base64_encode($password);
    }

    /**
     * Mock the AuthService decryptPassword method for testing.
     */
    protected function mockPasswordDecryption(): void
    {
        $this->partialMock(\Modules\Auth\Services\AuthService::class, function ($mock) {
            $mock->shouldReceive('decryptPassword')
                ->andReturnUsing(function ($encrypted) {
                    return base64_decode($encrypted);
                });
        });
    }

    /**
     * Create a real JWT token for testing protected routes.
     */
    protected function createMockJwtToken(object $user, string $role): string
    {
        $modelClass = get_class($user);

        $config = config('jwt-auth');
        $config['user_model'] = $modelClass;
        Config::set('jwt-auth.user_model', $modelClass);

        $jwtService = new \Kz370\JwtAuth\Services\JwtService($config);

        return $jwtService->generateAccessToken($user, [
            'role' => $role,
            // We don't strictly need 'rth' for middleware auth check, as it only validates signature and sub/exp
            // But if we did, we'd need a refresh token hash. For now, skip it.
        ]);
    }

    /**
     * Act as an authenticated API user via JWT.
     */
    protected function actingAsApiUser(object $user, string $role = 'student'): self
    {
        // Mock the JWT middleware to accept our test user
        $this->withHeader('Authorization', 'Bearer ' . $this->createMockJwtToken($user, $role));

        return $this;
    }
}
