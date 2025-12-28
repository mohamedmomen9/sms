<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FacultyResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_super_admin_can_list_faculties()
    {
        $admin = \App\Models\User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = \App\Models\User::factory()->create([
                'email' => 'admin@example.com', 
                'is_admin' => true, 
                'role' => 'admin'
            ]);
        }
        
        $admin->assignRole('Super Admin');

        $this->actingAs($admin);

        $response = $this->get('/admin/faculties');

        $response->assertStatus(200);
    }
}
