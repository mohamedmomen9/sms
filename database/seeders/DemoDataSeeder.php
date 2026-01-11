<?php

namespace Database\Seeders;

use Modules\Campus\Models\Campus;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;
use Modules\Users\Models\User;
use Modules\Teachers\Models\Teacher;
use Modules\Students\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (App::isProduction()) {
            $this->command->warn('Skipping demo data in production environment.');
            return;
        }

        $this->call(PermissionsSeeder::class);

        $superAdminRole = Role::where('name', 'Super Admin')->firstOrFail();
        $facultyAdminRole = Role::where('name', 'Faculty Admin')->firstOrFail();

        // 1. Create Super Admin (Dashboard User)
        $adminEmail = 'admin@university.edu';
        $admin = User::where('email', $adminEmail)->orWhere('username', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'username' => 'admin',
                'email' => $adminEmail,
                'password' => Hash::make('secret'),
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'display_name' => 'Super Administrator',
            ]);
            if ($superAdminRole) {
                $admin->assignRole($superAdminRole);
            }
        }

        // 2. Create Campuses
        $cairoCampus = Campus::firstOrCreate(
            ['code' => 'CAI'],
            [
                'name' => ['en' => 'Cairo Campus', 'ar' => 'فرع القاهرة'],
                'location' => 'Cairo, Egypt',
                'status' => 'active',
            ]
        );

        $alexCampus = Campus::firstOrCreate(
            ['code' => 'ALX'],
            [
                'name' => ['en' => 'Alexandria Campus', 'ar' => 'فرع الإسكندرية'],
                'location' => 'Alexandria, Egypt',
                'status' => 'active',
            ]
        );

        $facultiesData = [
            [
                'name' => 'School of Engineering',
                'code' => 'ENG',
                'campus_id' => $cairoCampus->id,
                'departments' => [
                    ['name' => 'Computer Science', 'code' => 'CS'],
                    ['name' => 'Mechanical Engineering', 'code' => 'ME'],
                ]
            ],
            [
                'name' => 'School of Science',
                'code' => 'SCI',
                'campus_id' => $cairoCampus->id,
                'departments' => [
                    ['name' => 'Physics', 'code' => 'PHY'],
                    ['name' => 'Mathematics', 'code' => 'MATH'],
                ]
            ],
            [
                'name' => 'School of Medicine',
                'code' => 'MED',
                'campus_id' => $alexCampus->id,
                'departments' => [
                    ['name' => 'General Medicine', 'code' => 'GM'],
                ]
            ]
        ];

        while (count($facultiesData) < 5) {
            $i = count($facultiesData) + 1;
            $facultiesData[] = [
                'name' => "Faculty of " . fake()->word(),
                'code' => "FAC-{$i}",
                'campus_id' => $cairoCampus->id,
                'departments' => [
                    ['name' => "Department of " . fake()->word(), 'code' => "DEP-{$i}"]
                ]
            ];
        }

        foreach ($facultiesData as $facData) {
            $faculty = Faculty::firstOrCreate(
                [
                    'code' => $facData['code']
                ],
                [
                    'name' => ['en' => $facData['name'], 'ar' => $facData['name']],
                    'campus_id' => $facData['campus_id'] ?? null,
                ]
            );

            // Create Faculty Admin (Dashboard User)
            $facEmail = strtolower($facData['code']) . '_admin@university.edu';
            $fAdmin = User::where('email', $facEmail)->first();
            if (!$fAdmin) {
                $fAdmin = User::create([
                    'username' => strtolower($facData['code']) . '_admin',
                    'email' => $facEmail,
                    'password' => Hash::make('secret'),
                    'first_name' => $facData['code'],
                    'last_name' => 'Admin',
                    'display_name' => "{$facData['name']} Admin",
                    'faculty_id' => $faculty->id,
                ]);
                if ($facultyAdminRole) {
                    $fAdmin->assignRole($facultyAdminRole);
                }
            }

            $departmentsData = $facData['departments'] ?? [['name' => 'General Department', 'code' => 'GEN']];
            
            foreach ($departmentsData as $deptData) {
                $department = Department::firstOrCreate(
                    [
                        'faculty_id' => $faculty->id,
                        'code' => $deptData['code']
                    ],
                    [
                        'name' => ['en' => $deptData['name'], 'ar' => $deptData['name']],
                        'status' => 'active'
                    ]
                );

                $curriculum = \Modules\Curriculum\Models\Curriculum::firstOrCreate(
                    ['code' => $deptData['code'] . '-STD'],
                    ['name' => ['en' => 'Standard Curriculum', 'ar' => 'Standard Curriculum'], 'status' => 'active']
                );
                
                // Attach department to curriculum via many-to-many relationship
                if (!$curriculum->departments()->where('department_id', $department->id)->exists()) {
                    $curriculum->departments()->attach($department->id);
                }
                
                // Attach faculty to curriculum via many-to-many relationship
                if (!$curriculum->faculties()->where('faculty_id', $faculty->id)->exists()) {
                    $curriculum->faculties()->attach($faculty->id);
                }

                $createdSubjects = [];
                for ($s = 1; $s <= 5; $s++) {
                    $subject = Subject::firstOrCreate(
                        [
                            'department_id' => $department->id,
                            'code' => $deptData['code'] . '-SUB-' . $s,
                        ],
                        [
                            'faculty_id' => $faculty->id,
                            'name' => ['en' => "Subject {$s} of " . $deptData['name'], 'ar' => "Subject {$s} (AR)"],
                        ]
                    );
                    $createdSubjects[] = $subject;
                    
                    // Attach subject to curriculum via pivot table
                    if (!$curriculum->subjects()->where('subject_id', $subject->id)->exists()) {
                        $curriculum->subjects()->attach($subject->id, ['is_mandatory' => true]);
                    }
                }

                // Create Teachers (using separate Teachers module)
                for ($t = 1; $t <= 5; $t++) {
                    $teacherEmail = strtolower("{$deptData['code']}_teacher_{$t}@university.edu");
                    Teacher::firstOrCreate(
                        ['email' => $teacherEmail],
                        [
                            'name' => "Teacher {$t} - {$deptData['name']}",
                            'password' => Hash::make('secret'),
                            'phone' => fake()->phoneNumber(),
                            'qualification' => 'PhD',
                            'campus_id' => $faculty->campus_id,
                            // 'school_id' => null, // Optional
                        ]
                    );
                    // Teachers don't need Roles from Spatie anymore unless we add a guard, but for now they are just records
                }

                // Create Students (using separate Students module)
                for ($st = 1; $st <= 5; $st++) {
                    $studentEmail = strtolower("{$deptData['code']}_student_{$st}@university.edu");
                    Student::firstOrCreate(
                        ['email' => $studentEmail],
                        [
                            'name' => "Student {$st} - {$deptData['name']}",
                            'password' => Hash::make('secret'),
                            'student_id' => "ST-{$deptData['code']}-" . fake()->unique()->numerify('#####'),
                            'date_of_birth' => fake()->date(),
                            'campus_id' => $faculty->campus_id,
                            // 'school_id' => null, // Optional
                        ]
                    );
                }
            }
        }
    }
}
