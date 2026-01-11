<?php

namespace Database\Seeders;

use Modules\Campus\Models\Campus;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;
use Modules\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AcademicStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            'view_any_campus', 'view_campus', 'create_campus', 'update_campus', 'delete_campus',
            'view_any_faculty', 'view_faculty', 'create_faculty', 'update_faculty', 'delete_faculty',
            'view_any_department', 'view_department', 'create_department', 'update_department', 'delete_department',
            'view_any_curriculum', 'view_curriculum', 'create_curriculum', 'update_curriculum', 'delete_curriculum',
            'view_any_subject', 'view_subject', 'create_subject', 'update_subject', 'delete_subject',
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);

        // Create User Role
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view_any_campus', 'view_campus',
            'view_any_faculty', 'view_faculty',
            'view_any_department', 'view_department',
            'view_any_curriculum', 'view_curriculum',
            'view_any_subject', 'view_subject',
        ]);

        // Create Campuses
        $campus1 = Campus::firstOrCreate(
            ['code' => 'MAIN'],
            [
                'name' => ['en' => 'Main Campus', 'ar' => 'Main Campus'],
                'location' => 'Giza',
                'address' => 'Giza, Egypt',
                'status' => 'active',
                'email' => 'main@example.com', 
                'phone' => '123456789',
            ]
        );

        $campus2 = Campus::firstOrCreate(
            ['code' => 'SMART'],
            [
                'name' => ['en' => 'Smart Village Campus', 'ar' => 'Smart Village Campus'],
                'location' => '6th October',
                'address' => 'Smart Village, Egypt',
                'status' => 'active',
                'email' => 'smart@example.com',
                'phone' => '987654321',
            ]
        );

        // Create Faculties
        $faculty1 = Faculty::firstOrCreate(
            ['code' => 'FAC001'],
            [
                'name' => ['en' => 'Faculty of Engineering', 'ar' => 'Faculty of Engineering'],
                'campus_id' => $campus1->id,
            ]
        );

        $faculty2 = Faculty::firstOrCreate(
            ['code' => 'FAC002'],
            [
                'name' => ['en' => 'Faculty of Science', 'ar' => 'Faculty of Science'],
                'campus_id' => $campus2->id,
            ]
        );

        // Create Departments
        $dept1 = Department::firstOrCreate(
            ['code' => 'DEP001', 'faculty_id' => $faculty1->id],
            ['name' => ['en' => 'Computer Engineering', 'ar' => 'Computer Engineering'], 'status' => 'active']
        );

        $dept2 = Department::firstOrCreate(
            ['code' => 'DEP002', 'faculty_id' => $faculty1->id],
            ['name' => ['en' => 'Electrical Engineering', 'ar' => 'Electrical Engineering'], 'status' => 'active']
        );

        $dept3 = Department::firstOrCreate(
            ['code' => 'DEP003', 'faculty_id' => $faculty2->id],
            ['name' => ['en' => 'Physics', 'ar' => 'Physics'], 'status' => 'active']
        );

        // Create Curricula
        $curr1 = \Modules\Curriculum\Models\Curriculum::firstOrCreate(
            ['department_id' => $dept1->id, 'name' => ['en' => '2024 Computer Engineering', 'ar' => '2024 Computer Engineering']],
            ['code' => 'CE-2024', 'status' => 'active']
        );
        // Link Curriculum 1 to Faculty 1
        if (!$curr1->faculties()->where('faculty_id', $faculty1->id)->exists()) {
            $curr1->faculties()->attach($faculty1->id);
        }

        $curr2 = \Modules\Curriculum\Models\Curriculum::firstOrCreate(
            ['department_id' => $dept2->id, 'name' => ['en' => '2024 Electrical Engineering', 'ar' => '2024 Electrical Engineering']],
            ['code' => 'EE-2024', 'status' => 'active']
        );
         // Link Curriculum 2 to Faculty 1
        if (!$curr2->faculties()->where('faculty_id', $faculty1->id)->exists()) {
            $curr2->faculties()->attach($faculty1->id);
        }

        // Create Subjects
        Subject::firstOrCreate(
            ['code' => 'CS101'],
            [
                'faculty_id' => $faculty1->id,
                'department_id' => $dept1->id,
                'curriculum_id' => $curr1->id,
                'curriculum' => '2024 Computer Engineering', // Legacy
                'name' => ['ar' => 'مقدمة في البرمجة', 'en' => 'Introduction to Programming'],
                'category' => 'core',
                'type' => 'mixed',
                'max_hours' => 3.0,
                'is_mandatory' => true,
            ]
        );

        Subject::firstOrCreate(
            ['code' => 'EE101'],
            [
                'faculty_id' => $faculty1->id,
                'department_id' => $dept2->id,
                'curriculum_id' => $curr2->id,
                'curriculum' => '2024 Electrical Engineering', // Legacy
                'name' => ['ar' => 'الدوائر الكهربائية', 'en' => 'Electric Circuits'],
                'category' => 'core',
                'type' => 'theoretical',
                'max_hours' => 4.0,
                'is_mandatory' => true,
            ]
        );

        // Create Admin User
        $adminUser = User::where('email', 'admin@university.edu')->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'username' => 'admin',
                'email' => 'admin@university.edu',
                'password' => Hash::make('secret'),
                'display_name' => 'System Administrator',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'role' => 'admin',
                'is_admin' => true,
            ]);
        }
        $adminUser->assignRole('admin');
        
        // Faculty Scoped User
        $facultyUser = User::firstOrCreate(
            ['email' => 'faculty.admin@university.edu'],
            [
                'username' => 'faculty_admin',
                'password' => Hash::make('secret'),
                'display_name' => 'Engineering Faculty Admin',
                'first_name' => 'Faculty',
                'last_name' => 'Admin',
                'role' => 'faculty_member',
                'is_admin' => false,
                'faculty_id' => $faculty1->id,
            ]
        );
        $facultyUser->assignRole('user');

        $this->command->info('Academic structure seeded successfully!');
    }
}
