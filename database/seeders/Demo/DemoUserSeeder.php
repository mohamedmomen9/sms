<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Models\User;
use Modules\Teachers\Models\Teacher;
use Modules\Students\Models\Student;
use Modules\Faculty\Models\Faculty;
use Modules\Department\Models\Department;
use Spatie\Permission\Models\Role;
use Database\Seeders\DemoDataSeeder;

class DemoUserSeeder extends Seeder
{
    private int $teacherNameIndex = 0;
    private int $studentNameIndex = 0;

    // Constants for configuration
    public const TEACHERS_PER_DEPT = 8;
    public const STUDENTS_PER_DEPT = 15;

    public function run(): void
    {
        $superAdminRole = Role::where('name', 'Super Admin')->firstOrFail();
        $facultyAdminRole = Role::where('name', 'Faculty Admin')->firstOrFail();

        // Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@university.edu'],
            [
                'username' => 'admin',
                'password' => Hash::make('secret'),
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'display_name' => 'Super Administrator',
            ]
        );
        if (!$admin->hasRole($superAdminRole)) {
            $admin->assignRole($superAdminRole);
        }

        $faculties = Faculty::with('departments')->get();

        foreach ($faculties as $faculty) {
            // Faculty Admin
            $fAdmin = User::firstOrCreate(
                ['email' => strtolower($faculty->code) . '_admin@university.edu'],
                [
                    'username' => strtolower($faculty->code) . '_admin',
                    'password' => Hash::make('secret'),
                    'first_name' => $faculty->code,
                    'last_name' => 'Admin',
                    'display_name' => "{$faculty->getTranslation('name', 'en')} Admin",
                    'faculty_id' => $faculty->id,
                ]
            );
            if (!$fAdmin->hasRole($facultyAdminRole)) {
                $fAdmin->assignRole($facultyAdminRole);
            }

            foreach ($faculty->departments as $department) {
                $this->createTeachers($faculty, $department);
                $this->createStudents($faculty, $department);
            }
        }
    }

    private function createTeachers($faculty, $department)
    {
        for ($t = 1; $t <= self::TEACHERS_PER_DEPT; $t++) {
            $teacherName = $this->getTeacherName();
            $teacher = Teacher::firstOrCreate(
                ['email' => strtolower("{$department->code}_teacher_{$t}@university.edu")],
                [
                    'name' => $teacherName,
                    'password' => Hash::make('secret'),
                    'phone' => $this->generatePhone($t),
                    'qualification' => ['PhD', 'MSc', 'Professor'][($t - 1) % 3],
                    'campus_id' => $faculty->campus_id,
                ]
            );
            $teacher->faculties()->syncWithoutDetaching([$faculty->id]);
        }
    }

    private function createStudents($faculty, $department)
    {
        for ($st = 1; $st <= self::STUDENTS_PER_DEPT; $st++) {
            $studentName = $this->getStudentName();
            Student::firstOrCreate(
                ['email' => strtolower("{$department->code}_student_{$st}@university.edu")],
                [
                    'name' => $studentName,
                    'password' => Hash::make('secret'),
                    'student_id' => "ST-{$department->code}-" . str_pad($st, 5, '0', STR_PAD_LEFT),
                    'date_of_birth' => $this->generateBirthDate($st),
                    'campus_id' => $faculty->campus_id,
                ]
            );
        }
    }

    // Helpers ported from original seeder
    private function getTeacherName(): string
    {
        $surName = DemoDataSeeder::TEACHER_SURNAMES[$this->teacherNameIndex % count(DemoDataSeeder::TEACHER_SURNAMES)];
        $this->teacherNameIndex++;
        return "Dr. {$surName}";
    }

    private function getStudentName(): string
    {
        $firstName = DemoDataSeeder::STUDENT_FIRST_NAMES[$this->studentNameIndex % count(DemoDataSeeder::STUDENT_FIRST_NAMES)];
        $lastName = DemoDataSeeder::STUDENT_LAST_NAMES[$this->studentNameIndex % count(DemoDataSeeder::STUDENT_LAST_NAMES)];
        $this->studentNameIndex++;
        return "{$firstName} {$lastName}";
    }

    private function generatePhone(int $seed): string
    {
        $prefix = DemoDataSeeder::PHONE_PREFIXES[$seed % count(DemoDataSeeder::PHONE_PREFIXES)];
        return $prefix . str_pad((string)$seed, 8, '0', STR_PAD_LEFT);
    }

    private function generateBirthDate(int $seed): string
    {
        $year = 2000 + ($seed % 5);
        $month = 1 + ($seed % 12);
        $day = 1 + ($seed % 28);
        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }
}
