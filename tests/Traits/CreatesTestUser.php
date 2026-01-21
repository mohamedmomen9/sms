<?php

namespace Tests\Traits;

use Modules\Users\Models\User;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;
use Modules\Campus\Models\Campus;
use Modules\Faculty\Models\Faculty;
use Modules\Department\Models\Department;
use Modules\Academic\Models\AcademicYear;
use Modules\Academic\Models\Term;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * Helper trait for creating test users with various roles.
 */
trait CreatesTestUser
{
    protected function createSuperAdmin(): User
    {
        $this->ensureRoleExists('Super Admin');

        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'is_admin' => true,
            'role' => 'admin',
        ]);
        $admin->assignRole('Super Admin');

        return $admin;
    }

    protected function createStudent(array $attributes = []): Student
    {
        $campus = $this->getOrCreateCampus();
        $faculty = $this->getOrCreateFaculty($campus);
        $department = $this->getOrCreateDepartment($faculty);

        return Student::create(array_merge([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'student_id' => fake()->unique()->numerify('STU#####'),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'campus_id' => $campus->id,
            'school_id' => $faculty->id,
            'department_id' => $department->id,
        ], $attributes));
    }

    protected function createTeacher(array $attributes = []): Teacher
    {
        $campus = $this->getOrCreateCampus();

        return Teacher::create(array_merge([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => fake()->phoneNumber(),
            'qualification' => fake()->randomElement(['PhD', 'Masters', 'Bachelor']),
            'campus_id' => $campus->id,
        ], $attributes));
    }

    protected function getOrCreateCampus(): Campus
    {
        return Campus::first() ?? Campus::create([
            'code' => 'MAIN',
            'name' => ['en' => 'Main Campus', 'ar' => 'الحرم الرئيسي'],
            'location' => 'Test Location',
            'status' => 'active',
        ]);
    }

    protected function getOrCreateFaculty(?Campus $campus = null): Faculty
    {
        $campus = $campus ?? $this->getOrCreateCampus();

        return Faculty::first() ?? Faculty::create([
            'campus_id' => $campus->id,
            'code' => 'ENG',
            'name' => ['en' => 'Engineering', 'ar' => 'الهندسة'],
        ]);
    }

    protected function getOrCreateDepartment(?Faculty $faculty = null): Department
    {
        $faculty = $faculty ?? $this->getOrCreateFaculty();

        return Department::first() ?? Department::create([
            'faculty_id' => $faculty->id,
            'code' => 'CS',
            'name' => ['en' => 'Computer Science', 'ar' => 'علوم الحاسب'],
            'status' => 'active',
        ]);
    }

    protected function getOrCreateAcademicYear(): AcademicYear
    {
        return AcademicYear::first() ?? AcademicYear::create([
            'name' => '2024-2025',
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'is_active' => true,
            'status' => 'active',
        ]);
    }

    protected function getOrCreateTerm(?AcademicYear $academicYear = null): Term
    {
        $academicYear = $academicYear ?? $this->getOrCreateAcademicYear();

        return Term::first() ?? Term::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'FALL',
            'start_date' => now(),
            'end_date' => now()->addMonths(4),
            'is_active' => true,
        ]);
    }

    protected function ensureRoleExists(string $roleName): Role
    {
        return Role::firstOrCreate(
            ['name' => $roleName, 'guard_name' => 'web']
        );
    }
}
