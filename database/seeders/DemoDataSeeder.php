<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\User;
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

        // 3. Create Campuses
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

            // Create Faculty Admin
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

                $curriculum = \App\Models\Curriculum::firstOrCreate(
                    ['department_id' => $department->id, 'name' => ['en' => 'Standard Curriculum', 'ar' => 'Standard Curriculum']],
                    ['code' => $deptData['code'] . '-STD', 'status' => 'active']
                );

                $createdSubjects = [];
                for ($s = 1; $s <= 5; $s++) {
                    $createdSubjects[] = Subject::firstOrCreate(
                        [
                            'department_id' => $department->id,
                            'code' => $deptData['code'] . '-SUB-' . $s,
                        ],
                        [
                            'faculty_id' => $faculty->id,
                            'curriculum_id' => $curriculum->id,
                            'name' => ['en' => "Subject {$s} of " . $deptData['name'], 'ar' => "Subject {$s} (AR)"],
                            'curriculum' => 'Standard',
                            'max_hours' => 3,
                            'category' => 'compulsory',
                            'type' => 'theoretical',
                        ]
                    );
                }

                for ($t = 1; $t <= 5; $t++) {
                    $teacherEmail = strtolower("{$deptData['code']}_teacher_{$t}@university.edu");
                    $teacher = User::firstOrCreate(
                        ['email' => $teacherEmail],
                        [
                            'username' => "{$deptData['code']}_teacher_{$t}",
                            'password' => Hash::make('secret'),
                            'first_name' => "Teacher {$t}",
                            'last_name' => $deptData['code'],
                            'display_name' => "Teacher {$t} of {$deptData['name']}",
                            'faculty_id' => $faculty->id,
                        ]
                    );
                    try { $teacher->assignRole('Teacher'); } catch (\Exception $e) {}
                    $subjectsToAssign = collect($createdSubjects)->random(rand(1, 3));
                    $teacher->subjects()->syncWithoutDetaching($subjectsToAssign->pluck('id'));
                }

                for ($st = 1; $st <= 5; $st++) {
                    $studentEmail = strtolower("{$deptData['code']}_student_{$st}@university.edu");
                    $student = User::firstOrCreate(
                        ['email' => $studentEmail],
                        [
                            'username' => "{$deptData['code']}_student_{$st}",
                            'password' => Hash::make('secret'),
                            'first_name' => "Student {$st}",
                            'last_name' => $deptData['code'],
                            'display_name' => "Student {$st} of {$deptData['name']}",
                            'faculty_id' => $faculty->id,
                        ]
                    );
                        try { $student->assignRole('Student'); } catch (\Exception $e) {}

                }
            }
        }
    }
}
