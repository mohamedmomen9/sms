<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Academic\Models\AcademicYear;
use Modules\Academic\Models\Term;
use Modules\Campus\Models\Campus;
use Modules\Faculty\Models\Faculty;
use Modules\Department\Models\Department;
use Modules\Curriculum\Models\Curriculum;
use Modules\Subject\Models\SessionType;

class DemoAcademicSeeder extends Seeder
{
    public function run(): void
    {
        // Session Types
        $sessionTypes = [
            ['code' => 'LECT', 'name' => 'Lecture', 'is_graded' => false],
            ['code' => 'LAB', 'name' => 'Laboratory', 'is_graded' => true],
            ['code' => 'TUT', 'name' => 'Tutorial', 'is_graded' => false],
            ['code' => 'SEM', 'name' => 'Seminar', 'is_graded' => false],
            ['code' => 'EXAM', 'name' => 'Exam', 'is_graded' => true],
        ];

        foreach ($sessionTypes as $type) {
            SessionType::firstOrCreate(['code' => $type['code']], $type);
        }

        // Academic Year & Terms
        $academicYear = AcademicYear::firstOrCreate(
            ['name' => '2025-2026'],
            ['start_date' => '2025-09-01', 'end_date' => '2026-06-30', 'is_active' => true]
        );

        Term::firstOrCreate(
            ['name' => 'FALL', 'academic_year_id' => $academicYear->id],
            ['start_date' => '2025-09-01', 'end_date' => '2026-01-31', 'is_active' => true]
        );

        Term::firstOrCreate(
            ['name' => 'SPRING', 'academic_year_id' => $academicYear->id],
            ['start_date' => '2026-02-01', 'end_date' => '2026-06-30', 'is_active' => false]
        );

        // Faculties & Departments
        $campuses = Campus::all()->keyBy('code');

        $facultiesData = [
            ['name' => 'School of Engineering', 'code' => 'ENG', 'campus_code' => 'CAI', 'departments' => [
                ['name' => 'Computer Science', 'code' => 'CS'],
                ['name' => 'Mechanical Engineering', 'code' => 'ME'],
                ['name' => 'Electrical Engineering', 'code' => 'EE'],
            ]],
            ['name' => 'School of Science', 'code' => 'SCI', 'campus_code' => 'CAI', 'departments' => [
                ['name' => 'Physics', 'code' => 'PHY'],
                ['name' => 'Mathematics', 'code' => 'MATH'],
                ['name' => 'Chemistry', 'code' => 'CHEM'],
            ]],
            ['name' => 'School of Medicine', 'code' => 'MED', 'campus_code' => 'ALX', 'departments' => [
                ['name' => 'General Medicine', 'code' => 'GM'],
                ['name' => 'Surgery', 'code' => 'SURG'],
            ]],
            ['name' => 'School of Business', 'code' => 'BUS', 'campus_code' => 'ALX', 'departments' => [
                ['name' => 'Accounting', 'code' => 'ACC'],
                ['name' => 'Marketing', 'code' => 'MKT'],
                ['name' => 'Finance', 'code' => 'FIN'],
            ]],
            ['name' => 'School of Arts', 'code' => 'ART', 'campus_code' => 'ASW', 'departments' => [
                ['name' => 'Fine Arts', 'code' => 'FA'],
                ['name' => 'Music', 'code' => 'MUS'],
            ]],
            ['name' => 'School of Law', 'code' => 'LAW', 'campus_code' => 'ASW', 'departments' => [
                ['name' => 'Public Law', 'code' => 'PL'],
                ['name' => 'Private Law', 'code' => 'PRL'],
            ]],
        ];

        foreach ($facultiesData as $facData) {
            $campus = $campuses->get($facData['campus_code']);
            if (!$campus) continue;

            $faculty = Faculty::firstOrCreate(
                ['code' => $facData['code']],
                [
                    'name' => ['en' => $facData['name'], 'ar' => $facData['name']],
                    'campus_id' => $campus->id,
                ]
            );

            foreach ($facData['departments'] as $deptData) {
                $department = Department::firstOrCreate(
                    ['faculty_id' => $faculty->id, 'code' => $deptData['code']],
                    ['name' => ['en' => $deptData['name'], 'ar' => $deptData['name']], 'status' => 'active']
                );

                $curriculum = Curriculum::firstOrCreate(
                    ['code' => $deptData['code'] . '-STD'],
                    ['name' => ['en' => 'Standard Curriculum', 'ar' => 'المنهج المعياري'], 'status' => 'active']
                );
                $curriculum->departments()->syncWithoutDetaching([$department->id]);
                $curriculum->faculties()->syncWithoutDetaching([$faculty->id]);
            }
        }
    }
}
