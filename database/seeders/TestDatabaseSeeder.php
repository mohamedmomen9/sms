<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Campus\Models\Campus;
use Modules\Faculty\Models\Faculty;
use Modules\Department\Models\Department;
use Modules\Academic\Models\AcademicYear;
use Modules\Academic\Models\Term;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Minimal seeder for testing environment.
 * Creates only the essential data needed for tests to run.
 */
class TestDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRolesAndPermissions();
        $this->seedCampusStructure();
        $this->seedAcademicStructure();
    }

    protected function seedRolesAndPermissions(): void
    {
        // Core roles needed for tests
        $roles = ['Super Admin', 'Admin', 'Teacher', 'Student'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Basic CRUD permissions for resources
        $resources = ['campus', 'faculty', 'department', 'subject', 'student', 'teacher', 'user'];
        $actions = ['view', 'create', 'update', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}_{$resource}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Assign all permissions to Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $superAdmin->syncPermissions(Permission::all());
    }

    protected function seedCampusStructure(): void
    {
        $campus = Campus::firstOrCreate(
            ['code' => 'MAIN'],
            [
                'name' => ['en' => 'Main Campus', 'ar' => 'الحرم الرئيسي'],
                'location' => 'Test Location',
                'status' => 'active',
            ]
        );

        $faculty = Faculty::firstOrCreate(
            ['code' => 'ENG'],
            [
                'campus_id' => $campus->id,
                'name' => ['en' => 'Engineering', 'ar' => 'الهندسة'],
            ]
        );

        Department::firstOrCreate(
            ['code' => 'CS'],
            [
                'faculty_id' => $faculty->id,
                'name' => ['en' => 'Computer Science', 'ar' => 'علوم الحاسب'],
                'status' => 'active',
            ]
        );
    }

    protected function seedAcademicStructure(): void
    {
        $academicYear = AcademicYear::firstOrCreate(
            ['name' => '2024-2025'],
            [
                'start_date' => '2024-09-01',
                'end_date' => '2025-06-30',
                'is_active' => true,
                'status' => 'active',
            ]
        );

        Term::firstOrCreate(
            ['academic_year_id' => $academicYear->id, 'name' => 'FALL'],
            [
                'start_date' => '2024-09-01',
                'end_date' => '2024-12-31',
                'is_active' => true,
            ]
        );
    }
}
