<?php

namespace Tests\Feature;

use Tests\TestCase;
use Modules\Users\Models\User;
use Modules\Campus\Models\Campus;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampusResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_super_admin_can_list_campuses()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::factory()->create(['email' => 'admin@example.com', 'is_admin' => true, 'role' => 'admin']);
        }

        // Ensure the role exists (should be seeded, but to be safe)
        if (!Role::where('name', 'Super Admin')->exists()) {
            Role::create(['name' => 'Super Admin']);
        }
        $admin->assignRole('Super Admin');

        $this->actingAs($admin);

        $response = $this->get('/admin/campuses');

        $response->assertStatus(200);
    }
}
