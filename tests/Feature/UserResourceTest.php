<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_super_admin_can_list_users()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com', 
            'is_admin' => true, 
            'role' => 'admin'
        ]);
        
        $admin->assignRole('Super Admin');

        $this->actingAs($admin);

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
    }
}
