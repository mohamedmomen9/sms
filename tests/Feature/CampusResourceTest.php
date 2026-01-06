<?php

namespace Tests\Feature;

use App\Models\User;
use Modules\Campus\Models\Campus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        if (!\Spatie\Permission\Models\Role::where('name', 'Super Admin')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'Super Admin']);
        }
        $admin->assignRole('Super Admin');

        $this->actingAs($admin);

        $response = $this->get('/admin/campuses');

        $response->assertStatus(200);
    }
}
