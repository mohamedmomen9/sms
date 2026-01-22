<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

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
            'campus',
            'faculty',
            'department',
            'subject',
            'user',
            'role',
            'permission',
            'curriculum',
            'academic_year',
            'term',
            'building',
            'room',
            'facility',
            'teacher',
            'student',
            'course_offering',
            'course_enrollment',
            'course_schedule',
            'session_type',
            'appointment',
            'appointment_slot',
            'appointment_department',
            'appointment_purpose',
            'service_request',
            'service_type',
            'payment_registration',
            'training_opportunity',
            'field_training',
            'assessment',
            'evaluation',
            'grievance',
        ];

        $actions = ['view_any', 'view', 'create', 'update', 'delete', 'delete_any'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}_{$resource}"]);
            }
        }
    }
}
