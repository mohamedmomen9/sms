<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $resources = [
            'university',
            'faculty',
            'department',
            'subject',
            'user',
            'role',
            'permission',
        ];

        foreach ($resources as $resource) {
            Permission::firstOrCreate(['name' => "view_any_{$resource}"]);
            Permission::firstOrCreate(['name' => "view_{$resource}"]);
            Permission::firstOrCreate(['name' => "create_{$resource}"]);
            Permission::firstOrCreate(['name' => "update_{$resource}"]);
            Permission::firstOrCreate(['name' => "delete_{$resource}"]);
            Permission::firstOrCreate(['name' => "delete_any_{$resource}"]);
        }

        // Create Super Admin Role
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $role->givePermissionTo(Permission::all());

        // Assign to Admin User
        $user = User::where('email', env('ADMIN_EMAIL', 'admin@university.edu'))->first();
        if ($user) {
            $user->assignRole($role);
        }
    }
}
