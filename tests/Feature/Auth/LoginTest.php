<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;

/**
 * Tests for the Auth API endpoints.
 * 
 * Note: Since AuthService uses RSA encryption for password decryption (private method),
 * we test API validation and structure without full login flow. 
 * Integration tests with real RSA keys would be added separately.
 */
class LoginTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser;

    public function test_login_validates_required_fields(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'password']);
    }

    public function test_login_fails_without_role(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'student@test.com',
            'password' => 'some_password',
        ]);

        $response->assertStatus(400)
            ->assertJsonPath('message', 'Role is required');
    }

    public function test_student_login_endpoint_exists(): void
    {
        $response = $this->postJson('/api/v1/auth/student/login', [
            'username' => 'test@example.com',
            'password' => 'test_password',
            'device_name' => 'test_device',
        ]);

        // Should not be 404 - endpoint exists
        $this->assertNotEquals(404, $response->status());
    }

    public function test_teacher_login_endpoint_exists(): void
    {
        $response = $this->postJson('/api/v1/auth/teacher/login', [
            'username' => 'test@example.com',
            'password' => 'test_password',
            'device_name' => 'test_device',
        ]);

        // Should not be 404 - endpoint exists
        $this->assertNotEquals(404, $response->status());
    }

    public function test_generic_login_endpoint_with_role_in_body(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'test@example.com',
            'password' => 'test_password',
            'role' => 'student',
        ]);

        // Should not be 404 - endpoint exists and accepts role in body
        $this->assertNotEquals(404, $response->status());
        // Should not complain about missing role
        $this->assertNotEquals('Role is required', $response->json('message'));
    }

    public function test_invalid_role_is_rejected(): void
    {
        $response = $this->postJson('/api/v1/auth/invalid_role/login', [
            'username' => 'test@example.com',
            'password' => 'test_password',
        ]);

        // Invalid role should return 404 (route not matched)
        $response->assertStatus(404);
    }

    public function test_staff_login_endpoint_exists(): void
    {
        $response = $this->postJson('/api/v1/auth/staff/login', [
            'username' => 'test@example.com',
            'password' => 'test_password',
            'device_name' => 'test_device',
        ]);

        // Staff is an alias for teacher - endpoint should exist
        $this->assertNotEquals(404, $response->status());
    }
}
