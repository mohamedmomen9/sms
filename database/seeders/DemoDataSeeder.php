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
use Database\Seeders\PermissionsSeeder;

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

        // 3. Create Academic Structure (Year & Term)
        $academicYear = \Modules\Academic\Models\AcademicYear::firstOrCreate(
            ['name' => '2025-2026'],
            ['start_date' => '2025-09-01', 'end_date' => '2026-06-30', 'is_active' => true]
        );

        $term = \Modules\Academic\Models\Term::firstOrCreate(
            ['name' => 'FALL', 'academic_year_id' => $academicYear->id],
            ['start_date' => '2025-09-01', 'end_date' => '2026-01-31', 'is_active' => true]
        );

        // 4. Create Facilities
        $building = \Modules\Campus\Models\Building::firstOrCreate(
            ['code' => 'ENG-BLOCK'],
            [
                'name' => 'Engineering Block',
                'campus_id' => $cairoCampus->id,
            ]
        );

        $facilityProjector = \Modules\Campus\Models\Facility::firstOrCreate(['name' => 'Projector']);
        $facilityWhiteboard = \Modules\Campus\Models\Facility::firstOrCreate(['name' => 'Whiteboard']);

        $rooms = [];
        for ($r = 1; $r <= 5; $r++) {
            $room = \Modules\Campus\Models\Room::firstOrCreate(
                ['room_code' => "EB-10{$r}"],
                [
                    'building_id' => $building->id,
                    'number' => "10{$r}",
                    'name' => "Lecture Hall {$r}",
                    'floor_number' => 1,
                    'type' => 'classroom',
                    'capacity' => 40,
                    'status' => 'active',
                ]
            );
            $room->facilities()->syncWithoutDetaching([$facilityProjector->id, $facilityWhiteboard->id]);
            $rooms[] = $room;
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

                // Create Teachers
                $teachers = [];
                for ($t = 1; $t <= 5; $t++) {
                    $teacherEmail = strtolower("{$deptData['code']}_teacher_{$t}@university.edu");
                    $teachers[] = Teacher::firstOrCreate(
                        ['email' => $teacherEmail],
                        [
                            'name' => "Teacher {$t} - {$deptData['name']}",
                            'password' => Hash::make('secret'),
                            'phone' => fake()->phoneNumber(),
                            'qualification' => 'PhD',
                            'campus_id' => $faculty->campus_id,
                        ]
                    );
                }

                // Create Students
                $students = [];
                for ($st = 1; $st <= 5; $st++) {
                    $studentEmail = strtolower("{$deptData['code']}_student_{$st}@university.edu");
                    $students[] = Student::firstOrCreate(
                        ['email' => $studentEmail],
                        [
                            'name' => "Student {$st} - {$deptData['name']}",
                            'password' => Hash::make('secret'),
                            'student_id' => "ST-{$deptData['code']}-" . fake()->unique()->numerify('#####'),
                            'date_of_birth' => fake()->date(),
                            'campus_id' => $faculty->campus_id,
                        ]
                    );
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
                    
                    // Attach subject to curriculum
                    if (!$curriculum->subjects()->where('subject_id', $subject->id)->exists()) {
                        $curriculum->subjects()->attach($subject->id, ['is_mandatory' => true]);
                    }

                    // Create Course Offering with schedule
                    $teacher = $teachers[($s - 1) % count($teachers)];
                    $room = $rooms[($s - 1) % count($rooms)];

                    // Generate schedule based on subject index
                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
                    $timeSlots = [
                        ['start_time' => '08:00:00', 'end_time' => '09:30:00'],
                        ['start_time' => '10:00:00', 'end_time' => '11:30:00'],
                        ['start_time' => '12:00:00', 'end_time' => '13:30:00'],
                        ['start_time' => '14:00:00', 'end_time' => '15:30:00'],
                        ['start_time' => '16:00:00', 'end_time' => '17:30:00'],
                    ];

                    // Each subject gets 2 sessions per week
                    $primaryDayIndex = ($s - 1) % count($days);
                    $secondaryDayIndex = ($primaryDayIndex + 2) % count($days);
                    $timeSlotIndex = ($s - 1) % count($timeSlots);

                    $offering = \Modules\Subject\Models\CourseOffering::firstOrCreate(
                        [
                            'term_id' => $term->id,
                            'subject_id' => $subject->id,
                            'section_number' => '01',
                        ],
                        [
                            'teacher_id' => $teacher->id,
                            'room_id' => $room->id,
                            'capacity' => 40,
                        ]
                    );

                    // Create schedule entries in the course_schedules table
                    \Modules\Subject\Models\CourseSchedule::firstOrCreate(
                        [
                            'course_offering_id' => $offering->id,
                            'day' => $days[$primaryDayIndex],
                            'start_time' => $timeSlots[$timeSlotIndex]['start_time'],
                        ],
                        [
                            'end_time' => $timeSlots[$timeSlotIndex]['end_time'],
                        ]
                    );

                    \Modules\Subject\Models\CourseSchedule::firstOrCreate(
                        [
                            'course_offering_id' => $offering->id,
                            'day' => $days[$secondaryDayIndex],
                            'start_time' => $timeSlots[$timeSlotIndex]['start_time'],
                        ],
                        [
                            'end_time' => $timeSlots[$timeSlotIndex]['end_time'],
                        ]
                    );

                    // Enroll Students
                    foreach ($students as $student) {
                        \Modules\Students\Models\CourseEnrollment::firstOrCreate(
                            ['student_id' => $student->id, 'course_offering_id' => $offering->id],
                            ['status' => 'enrolled', 'enrolled_at' => now()]
                        );
                    }
                }
            }
        }
    }
}
