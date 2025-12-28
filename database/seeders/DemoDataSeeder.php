<?php

namespace Database\Seeders;

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
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::isProduction()) {
            $this->command->warn('Skipping demo data in production environment.');
            return;
        }

        // Ensure permissions and roles exist
        $this->call(PermissionsSeeder::class);

        // 1. Fetch Roles
        $superAdminRole = Role::where('name', 'Super Admin')->firstOrFail();
        $facultyAdminRole = Role::where('name', 'Faculty Admin')->firstOrFail();
        // $universityAdminRole removed

        // 2. Create Super Admin User
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
            
            // Only assign if role exists
            if ($superAdminRole) {
                $admin->assignRole($superAdminRole);
            }
        }

        // 3. Create Faculties (Ensure at least 5 for demo)
        $facultiesData = [
            [
                'name' => 'School of Engineering',
                'code' => 'ENG',
                'departments' => [
                    ['name' => 'Computer Science', 'code' => 'CS'],
                    ['name' => 'Mechanical Engineering', 'code' => 'ME'],
                ]
            ],
            [
                'name' => 'School of Science',
                'code' => 'SCI',
                'departments' => [
                    ['name' => 'Physics', 'code' => 'PHY'],
                    ['name' => 'Mathematics', 'code' => 'MATH'],
                ]
            ]
        ];

        while (count($facultiesData) < 5) {
            $i = count($facultiesData) + 1;
            $facultiesData[] = [
                'name' => "Faculty of " . fake()->word(),
                'code' => "FAC-{$i}",
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
                    'name' => ['en' => $facData['name'], 'ar' => $facData['name']]
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
                
                // Assign role if exists
                if ($facultyAdminRole) {
                    $fAdmin->assignRole($facultyAdminRole);
                }
            }

            // Create Departments
            $departmentsData = $facData['departments'] ?? [['name' => 'General Department', 'code' => 'GEN']];
            
            foreach ($departmentsData as $deptData) {
                // Create Department
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

                // Create Curriculum
                $curriculum = \App\Models\Curriculum::firstOrCreate(
                    ['department_id' => $department->id, 'name' => ['en' => 'Standard Curriculum', 'ar' => 'Standard Curriculum']],
                    ['code' => $deptData['code'] . '-STD', 'status' => 'active']
                );

                // Create at least 5 subjects per department
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
                            'curriculum' => 'Standard', // Legacy
                            'max_hours' => 3,
                            'category' => 'compulsory',
                            'type' => 'theoretical',
                        ]
                    );
                }

                // Create 5 Teachers for this Department
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
                        
                        // Check if Teacher role exists before assigning
                         try { $teacher->assignRole('Teacher'); } catch (\Exception $e) {}
                        
                        // Assign random subjects from this department if checking for new user or just always?
                        // If firstOrCreate returned existing, maybe skip subject attachment to avoid dups?
                        // detach first?
                        // Just attach to avoid complication, subjects table handles it? No, subject_user is pivot.
                        // We'll leave it as is, but only if it was just created?
                        // firstOrCreate doesn't tell us if it was created easily.
                        // Let's just sync?
                        $subjectsToAssign = collect($createdSubjects)->random(rand(1, 3));
                        $teacher->subjects()->syncWithoutDetaching($subjectsToAssign->pluck('id'));
                }

                // Create 5 Students for this Department 
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
