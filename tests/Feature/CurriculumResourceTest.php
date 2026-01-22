<?php

namespace Tests\Feature;

use Tests\TestCase;
use Modules\Users\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurriculumResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_super_admin_can_list_curricula()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::factory()->create([
                'email' => 'admin@example.com',
                'is_admin' => true,
                'role' => 'admin'
            ]);
        }

        $admin->assignRole('Super Admin');

        $this->actingAs($admin);

        $response = $this->get('/admin/curricula');

        $response->assertStatus(200);
    }
}
