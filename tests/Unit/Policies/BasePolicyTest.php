<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use Tests\Traits\CreatesTestUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Policies\CampusPolicy;
use Modules\Users\Models\User;
use Modules\Campus\Models\Campus;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BasePolicyTest extends TestCase
{
    use RefreshDatabase, CreatesTestUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedPermissions();
    }

    /**
     * Seed the minimum permissions needed for policy tests.
     */
    protected function seedPermissions(): void
    {
        // Create necessary permissions
        $permissions = [
            'view_any_campus',
            'view_campus',
            'create_campus',
            'update_campus',
            'delete_campus',
            'scope:global', // Required by HasAcademicScope trait
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Super Admin role with all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());
    }

    public function test_super_admin_can_view_any_resource(): void
    {
        $admin = $this->createSuperAdmin();
        $policy = new CampusPolicy();

        $this->assertTrue($policy->viewAny($admin));
    }

    public function test_user_without_permission_cannot_view_any(): void
    {
        $user = User::factory()->create();
        $policy = new CampusPolicy();

        $this->assertFalse($policy->viewAny($user));
    }

    public function test_user_with_permission_can_view_any(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view_any_campus');

        $policy = new CampusPolicy();

        $this->assertTrue($policy->viewAny($user));
    }

    public function test_user_with_create_permission_can_create(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('create_campus');

        $policy = new CampusPolicy();

        $this->assertTrue($policy->create($user));
    }

    public function test_user_without_create_permission_cannot_create(): void
    {
        $user = User::factory()->create();
        $policy = new CampusPolicy();

        $this->assertFalse($policy->create($user));
    }

    public function test_admin_user_with_update_permission_can_update(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $user->givePermissionTo(['update_campus', 'scope:global']);

        $campus = $this->getOrCreateCampus();
        $policy = new CampusPolicy();

        $this->assertTrue($policy->update($user, $campus));
    }

    public function test_admin_user_with_delete_permission_can_delete(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $user->givePermissionTo(['delete_campus', 'scope:global']);

        $campus = $this->getOrCreateCampus();
        $policy = new CampusPolicy();

        $this->assertTrue($policy->delete($user, $campus));
    }
}
