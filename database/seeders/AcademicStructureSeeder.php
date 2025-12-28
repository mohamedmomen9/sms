<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\University;
use App\Models\User;
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
            // University permissions
            'view_any_university',
            'view_university',
            'create_university',
            'update_university',
            'delete_university',
            // Faculty permissions
            'view_any_faculty',
            'view_faculty',
            'create_faculty',
            'update_faculty',
            'delete_faculty',
            // Department permissions
            'view_any_department',
            'view_department',
            'create_department',
            'update_department',
            'delete_department',
            // Subject permissions
            'view_any_subject',
            'view_subject',
            'create_subject',
            'update_subject',
            'delete_subject',
            // User permissions
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Admin Role with all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);

        // Create Standard User Role with view permissions
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view_any_university',
            'view_university',
            'view_any_faculty',
            'view_faculty',
            'view_any_department',
            'view_department',
            'create_department',
            'update_department',
            'view_any_subject',
            'view_subject',
            'create_subject',
            'update_subject',
        ]);

        // Create Universities
        $university1 = University::firstOrCreate(
            ['code' => 'UNI001'],
            [
                'name' => 'Cairo University',
                'logo' => null,
            ]
        );

        $university2 = University::firstOrCreate(
            ['code' => 'UNI002'],
            [
                'name' => 'Alexandria University',
                'logo' => null,
            ]
        );

        // Create Faculties for University 1
        $faculty1 = Faculty::firstOrCreate(
            ['code' => 'FAC001', 'university_id' => $university1->id],
            ['name' => 'Faculty of Engineering']
        );

        $faculty2 = Faculty::firstOrCreate(
            ['code' => 'FAC002', 'university_id' => $university1->id],
            ['name' => 'Faculty of Science']
        );

        // Create Faculties for University 2
        $faculty3 = Faculty::firstOrCreate(
            ['code' => 'FAC003', 'university_id' => $university2->id],
            ['name' => 'Faculty of Medicine']
        );

        // Create Departments
        $dept1 = Department::firstOrCreate(
            ['code' => 'DEP001', 'faculty_id' => $faculty1->id],
            [
                'name' => 'Computer Engineering',
                'status' => 'active',
            ]
        );

        $dept2 = Department::firstOrCreate(
            ['code' => 'DEP002', 'faculty_id' => $faculty1->id],
            [
                'name' => 'Electrical Engineering',
                'status' => 'active',
            ]
        );

        $dept3 = Department::firstOrCreate(
            ['code' => 'DEP003', 'faculty_id' => $faculty2->id],
            [
                'name' => 'Physics',
                'status' => 'active',
            ]
        );

        // Create Subjects
        Subject::firstOrCreate(
            ['code' => 'CS101'],
            [
                'faculty_id' => $faculty1->id,
                'department_id' => $dept1->id,
                'curriculum' => '2024',
                'name_ar' => 'مقدمة في البرمجة',
                'name_en' => 'Introduction to Programming',
                'category' => 'core',
                'type' => 'mixed',
                'max_hours' => 3.0,
            ]
        );

        Subject::firstOrCreate(
            ['code' => 'CS102'],
            [
                'faculty_id' => $faculty1->id,
                'department_id' => $dept1->id,
                'curriculum' => '2024',
                'name_ar' => 'هياكل البيانات',
                'name_en' => 'Data Structures',
                'category' => 'core',
                'type' => 'mixed',
                'max_hours' => 3.0,
            ]
        );

        $subject3 = Subject::firstOrCreate(
            ['code' => 'EE101'],
            [
                'faculty_id' => $faculty1->id,
                'department_id' => $dept2->id,
                'curriculum' => '2024',
                'name_ar' => 'الدوائر الكهربائية',
                'name_en' => 'Electric Circuits',
                'category' => 'core',
                'type' => 'theoretical',
                'max_hours' => 4.0,
            ]
        );

        Subject::firstOrCreate(
            ['code' => 'PHY101'],
            [
                'faculty_id' => $faculty2->id,
                'department_id' => $dept3->id,
                'curriculum' => '2024',
                'name_ar' => 'الفيزياء العامة',
                'name_en' => 'General Physics',
                'category' => 'core',
                'type' => 'mixed',
                'max_hours' => 4.0,
            ]
        );

        // Create Admin User - update existing or create new
        $adminUser = User::where('email', 'admin@example.com')
            ->orWhere('username', 'admin')
            ->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'display_name' => 'System Administrator',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'role' => 'admin',
                'is_admin' => true,
            ]);
        } else {
            $adminUser->update([
                'is_admin' => true,
                'role' => 'admin',
            ]);
        }
        $adminUser->assignRole('admin');

        // Create University-Scoped User
        $universityUser = User::firstOrCreate(
            ['email' => 'university.admin@example.com'],
            [
                'username' => 'university_admin',
                'password' => Hash::make('password'),
                'display_name' => 'Cairo University Admin',
                'first_name' => 'University',
                'last_name' => 'Admin',
                'role' => 'faculty_member',
                'is_admin' => false,
                'university_id' => $university1->id,
            ]
        );
        $universityUser->assignRole('user');

        // Create Faculty-Scoped User
        $facultyUser = User::firstOrCreate(
            ['email' => 'faculty.admin@example.com'],
            [
                'username' => 'faculty_admin',
                'password' => Hash::make('password'),
                'display_name' => 'Engineering Faculty Admin',
                'first_name' => 'Faculty',
                'last_name' => 'Admin',
                'role' => 'faculty_member',
                'is_admin' => false,
                'faculty_id' => $faculty1->id,
            ]
        );
        $facultyUser->assignRole('user');

        // Create Subject-Scoped User
        $subjectUser = User::firstOrCreate(
            ['email' => 'subject.user@example.com'],
            [
                'username' => 'subject_user',
                'password' => Hash::make('password'),
                'display_name' => 'Subject Coordinator',
                'first_name' => 'Subject',
                'last_name' => 'User',
                'role' => 'faculty_member',
                'is_admin' => false,
                'subject_id' => $subject3->id,
            ]
        );
        $subjectUser->assignRole('user');

        $this->command->info('Academic structure seeded successfully!');
        $this->command->info('Users created:');
        $this->command->info('- admin@example.com (Admin - Global Access)');
        $this->command->info('- university.admin@example.com (University Scoped - Cairo University)');
        $this->command->info('- faculty.admin@example.com (Faculty Scoped - Faculty of Engineering)');
        $this->command->info('- subject.user@example.com (Subject Scoped - Electric Circuits)');
        $this->command->info('Password for all users: password');
    }
}
