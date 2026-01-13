<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Modules\Users\Models\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Scopes
        $scopes = [
            'scope:global', // Used for Super Admin
            'scope:faculty',
            'scope:department',
            'scope:subject',
        ];
        foreach ($scopes as $scope) {
            Permission::firstOrCreate(['name' => $scope]);
        }

        // Functional permissions
        $resources = [
            'faculty',
            'department',
            'subject',
            'user',
            'role',
            'permission',
            'curriculum',
            'buildings',
            'rooms',
            'facilities',
        ];

        $actions = ['view_any', 'view', 'create', 'update', 'delete', 'delete_any'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}_{$resource}"]);
            }
        }

        // Roles and their permissions
        
        // Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Faculty Admin
        $facAdmin = Role::firstOrCreate(['name' => 'Faculty Admin']);
        $facAdmin->givePermissionTo('scope:faculty');
        $facAdmin->givePermissionTo(Permission::where('name', 'like', '%_department')->where('name', 'not like', 'scope:%')->get());
        $facAdmin->givePermissionTo(Permission::where('name', 'like', '%_subject')->where('name', 'not like', 'scope:%')->get());
        $facAdmin->givePermissionTo(Permission::where('name', 'like', '%_user')->where('name', 'not like', 'scope:%')->get());
        $facAdmin->givePermissionTo(['view_faculty', 'view_any_faculty']);

        // Teacher (Subject Scope)
        $teacher = Role::firstOrCreate(['name' => 'Teacher']);
        $teacher->givePermissionTo('scope:subject');
        $teacher->givePermissionTo(['view_subject', 'view_any_subject']);

        // Student (No specific management scope, maybe just basic view?)
        $student = Role::firstOrCreate(['name' => 'Student']);
        // $student->givePermissionTo('scope:subject'); // Example

        // Assign Super Admin to default user if exists
        $user = \Modules\Users\Models\User::where('email', env('ADMIN_EMAIL', 'admin@university.edu'))->first();
        if ($user) {
            $user->assignRole($superAdmin);
        }
    }
}
