<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        // Super Admin gets all permissions
        $superAdmin->syncPermissions(Permission::all());

        // 2. Faculty Admin
        $facAdmin = Role::firstOrCreate(['name' => 'Faculty Admin']);
        $facAdmin->syncPermissions([
            'scope:faculty',
            ...Permission::where('name', 'like', '%_department')->where('name', 'not like', 'scope:%')->pluck('name')->toArray(),
            ...Permission::where('name', 'like', '%_subject')->where('name', 'not like', 'scope:%')->pluck('name')->toArray(),
            ...Permission::where('name', 'like', '%_user')->where('name', 'not like', 'scope:%')->pluck('name')->toArray(),
            'view_faculty',
            'view_any_faculty'
        ]);

        // 3. Teacher
        $teacher = Role::firstOrCreate(['name' => 'Teacher']);
        $teacher->syncPermissions(['scope:subject', 'view_subject', 'view_any_subject']);

        // 4. Student
        $student = Role::firstOrCreate(['name' => 'Student']);
        // Assign students specific permissions if needed
    }
}
