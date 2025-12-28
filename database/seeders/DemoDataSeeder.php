<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Role;
use App\Models\Subject;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        // 1. Create Roles if they don't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $universityAdminRole = Role::firstOrCreate(['name' => 'University Admin', 'guard_name' => 'web']);
        $facultyAdminRole = Role::firstOrCreate(['name' => 'Faculty Admin', 'guard_name' => 'web']);

        // 2. Create Super Admin User
        $adminEmail = 'admin@university.edu';
        if (!User::where('email', $adminEmail)->exists()) {
            $admin = User::create([
                'username' => 'admin',
                'email' => $adminEmail,
                'password' => Hash::make('secret'),
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'display_name' => 'Super Administrator',
                'is_admin' => true,
            ]);
            
            $admin->assignRole($superAdminRole);
        }

        // 3. Create Demo Universities with Faculties, Departments, and Subjects
        $universities = [
            [
                'code' => 'MIT', 
                'name' => 'Massachusetts Institute of Technology',
                'faculties' => [
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
                ]
            ],
            [
                'code' => 'STAN', 
                'name' => 'Stanford University',
                'faculties' => [
                    [
                        'name' => 'School of Medicine',
                        'code' => 'MED',
                        'departments' => [
                            ['name' => 'Genetics', 'code' => 'GEN'],
                        ]
                    ]
                ]
            ],
            ['code' => 'HARV', 'name' => 'Harvard University'],
            ['code' => 'OXF', 'name' => 'University of Oxford'],
            ['code' => 'CAM', 'name' => 'University of Cambridge'],
            ['code' => 'CAL', 'name' => 'California Institute of Technology'],
            ['code' => 'ETH', 'name' => 'ETH Zurich'],
            ['code' => 'UCL', 'name' => 'University College London'],
            ['code' => 'IMP', 'name' => 'Imperial College London'],
            ['code' => 'CHI', 'name' => 'University of Chicago'],
        ];

        foreach ($universities as $uniData) {
            $university = University::firstOrCreate(
                ['code' => $uniData['code']],
                ['name' => $uniData['name']]
            );

            // Create Uni Admin
            $uniEmail = strtolower($uniData['code']) . '_admin@university.edu';
            if (!User::where('email', $uniEmail)->exists()) {
                $uAdmin = User::create([
                    'username' => strtolower($uniData['code']) . '_admin',
                    'email' => $uniEmail,
                    'password' => Hash::make('secret'),
                    'first_name' => $uniData['code'],
                    'last_name' => 'Admin',
                    'display_name' => "{$uniData['name']} Admin",
                    'is_admin' => false,
                    'university_id' => $university->id,
                ]);
                $uAdmin->assignRole($universityAdminRole);
            }

            // Create Faculties if defined
            if (isset($uniData['faculties'])) {
                foreach ($uniData['faculties'] as $facData) {
                    $faculty = Faculty::firstOrCreate(
                        [
                            'university_id' => $university->id,
                            'code' => $facData['code']
                        ],
                        [
                            'name' => $facData['name']
                        ]
                    );

                    // Create Faculty Admin
                    $facEmail = strtolower($uniData['code'] . '_' . $facData['code']) . '_admin@university.edu';
                    if (!User::where('email', $facEmail)->exists()) {
                        $fAdmin = User::create([
                            'username' => strtolower($uniData['code'] . '_' . $facData['code']) . '_admin',
                            'email' => $facEmail,
                            'password' => Hash::make('secret'),
                            'first_name' => $facData['code'],
                            'last_name' => 'Admin',
                            'display_name' => "{$facData['name']} Admin",
                            'is_admin' => false,
                            'university_id' => $university->id, // Ideally should be set, but scope might rely on faculty_id
                            'faculty_id' => $faculty->id,
                        ]);
                        $fAdmin->assignRole($facultyAdminRole);
                    }

                    // Create Departments
                    if (isset($facData['departments'])) {
                        foreach ($facData['departments'] as $deptData) {
                            $department = Department::firstOrCreate(
                                [
                                    'faculty_id' => $faculty->id,
                                    'code' => $deptData['code']
                                ],
                                [
                                    'name' => $deptData['name'],
                                    'status' => 'active'
                                ]
                            );

                            // Create random subjects for department
                            $subjects = ['Introduction to ', 'Advanced ', 'Principles of ', 'History of '];
                            foreach ($subjects as $prefix) {
                                Subject::firstOrCreate(
                                    [
                                        'department_id' => $department->id,
                                        'code' => $deptData['code'] . '-' . rand(100, 999),
                                    ],
                                    [
                                        'faculty_id' => $faculty->id, // Some schemas might require this duplication or it might be nullable
                                        'name_en' => $prefix . $deptData['name'],
                                        'name_ar' => $prefix . $deptData['name'] . ' (AR)',
                                        'curriculum' => 'Standard Curriculum',
                                        'max_hours' => 3,
                                        'category' => 'compulsory', // default enum likely
                                        'type' => 'theoretical', // default enum
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }
    }
}
