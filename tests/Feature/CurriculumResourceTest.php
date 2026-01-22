<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        $admin = \Modules\Users\Models\User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = \Modules\Users\Models\User::factory()->create([
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
