<?php

namespace Database\Seeders;

use Modules\Campus\Models\Campus;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;
use Modules\Subject\Models\SessionType;
use Modules\Subject\Models\CourseOffering;
use Modules\Subject\Models\CourseSchedule;
use Modules\Users\Models\User;
use Modules\Teachers\Models\Teacher;
use Modules\Students\Models\Student;
use Modules\Students\Models\CourseEnrollment;
use Modules\Academic\Models\AcademicYear;
use Modules\Academic\Models\Term;
use Modules\Campus\Models\Building;
use Modules\Campus\Models\Room;
use Modules\Campus\Models\Facility;
use Modules\Curriculum\Models\Curriculum;
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
        $this->seedSessionTypes();

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

        // Campuses - increased from 2 to 3
        $campuses = [
            Campus::firstOrCreate(['code' => 'CAI'], [
                'name' => ['en' => 'Cairo Campus', 'ar' => 'فرع القاهرة'],
                'location' => 'Cairo, Egypt',
                'status' => 'active',
            ]),
            Campus::firstOrCreate(['code' => 'ALX'], [
                'name' => ['en' => 'Alexandria Campus', 'ar' => 'فرع الإسكندرية'],
                'location' => 'Alexandria, Egypt',
                'status' => 'active',
            ]),
            Campus::firstOrCreate(['code' => 'ASW'], [
                'name' => ['en' => 'Aswan Campus', 'ar' => 'فرع أسوان'],
                'location' => 'Aswan, Egypt',
                'status' => 'active',
            ]),
        ];

        // Academic Structure
        $academicYear = AcademicYear::firstOrCreate(
            ['name' => '2025-2026'],
            ['start_date' => '2025-09-01', 'end_date' => '2026-06-30', 'is_active' => true]
        );

        $terms = [
            Term::firstOrCreate(
                ['name' => 'FALL', 'academic_year_id' => $academicYear->id],
                ['start_date' => '2025-09-01', 'end_date' => '2026-01-31', 'is_active' => true]
            ),
            Term::firstOrCreate(
                ['name' => 'SPRING', 'academic_year_id' => $academicYear->id],
                ['start_date' => '2026-02-01', 'end_date' => '2026-06-30', 'is_active' => false]
            ),
        ];
        $activeTerm = $terms[0];

        // Facilities
        $facilities = [
            Facility::firstOrCreate(['name' => 'Projector']),
            Facility::firstOrCreate(['name' => 'Whiteboard']),
            Facility::firstOrCreate(['name' => 'Air Conditioning']),
            Facility::firstOrCreate(['name' => 'Computer Lab Equipment']),
        ];

        // Buildings per campus - 2 buildings each
        $allRooms = [];
        foreach ($campuses as $campus) {
            for ($b = 1; $b <= 2; $b++) {
                $building = Building::firstOrCreate(
                    ['code' => $campus->code . "-BLD{$b}"],
                    ['name' => "{$campus->code} Building {$b}", 'campus_id' => $campus->id]
                );

                // 8 rooms per building
                for ($r = 1; $r <= 8; $r++) {
                    $roomTypes = ['classroom', 'lab', 'auditorium', 'classroom']; // mapped to allowed enums
                    $room = Room::firstOrCreate(
                        ['room_code' => "{$building->code}-{$b}0{$r}"],
                        [
                            'building_id' => $building->id,
                            'number' => "{$b}0{$r}",
                            'name' => "Room {$b}0{$r}",
                            'floor_number' => $b,
                            'type' => $roomTypes[($r - 1) % count($roomTypes)],
                            'capacity' => rand(30, 60),
                            'status' => 'active',
                        ]
                    );
                    $room->facilities()->syncWithoutDetaching(
                        collect($facilities)->random(rand(2, 4))->pluck('id')->toArray()
                    );
                    $allRooms[] = $room;
                }
            }
        }

        // Faculties - expanded from 3 to 6
        $facultiesData = [
            ['name' => 'School of Engineering', 'code' => 'ENG', 'campus' => $campuses[0], 'departments' => [
                ['name' => 'Computer Science', 'code' => 'CS'],
                ['name' => 'Mechanical Engineering', 'code' => 'ME'],
                ['name' => 'Electrical Engineering', 'code' => 'EE'],
            ]],
            ['name' => 'School of Science', 'code' => 'SCI', 'campus' => $campuses[0], 'departments' => [
                ['name' => 'Physics', 'code' => 'PHY'],
                ['name' => 'Mathematics', 'code' => 'MATH'],
                ['name' => 'Chemistry', 'code' => 'CHEM'],
            ]],
            ['name' => 'School of Medicine', 'code' => 'MED', 'campus' => $campuses[1], 'departments' => [
                ['name' => 'General Medicine', 'code' => 'GM'],
                ['name' => 'Surgery', 'code' => 'SURG'],
            ]],
            ['name' => 'School of Business', 'code' => 'BUS', 'campus' => $campuses[1], 'departments' => [
                ['name' => 'Accounting', 'code' => 'ACC'],
                ['name' => 'Marketing', 'code' => 'MKT'],
                ['name' => 'Finance', 'code' => 'FIN'],
            ]],
            ['name' => 'School of Arts', 'code' => 'ART', 'campus' => $campuses[2], 'departments' => [
                ['name' => 'Fine Arts', 'code' => 'FA'],
                ['name' => 'Music', 'code' => 'MUS'],
            ]],
            ['name' => 'School of Law', 'code' => 'LAW', 'campus' => $campuses[2], 'departments' => [
                ['name' => 'Public Law', 'code' => 'PL'],
                ['name' => 'Private Law', 'code' => 'PRL'],
            ]],
        ];

        $sessionTypes = SessionType::all()->keyBy('code');
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $timeSlots = [
            ['08:00', '09:30'], ['10:00', '11:30'], ['12:00', '13:30'],
            ['14:00', '15:30'], ['16:00', '17:30'],
        ];

        foreach ($facultiesData as $facData) {
            $faculty = Faculty::firstOrCreate(
                ['code' => $facData['code']],
                [
                    'name' => ['en' => $facData['name'], 'ar' => $facData['name']],
                    'campus_id' => $facData['campus']->id,
                ]
            );

            // Faculty Admin
            $fAdmin = User::firstOrCreate(
                ['email' => strtolower($facData['code']) . '_admin@university.edu'],
                [
                    'username' => strtolower($facData['code']) . '_admin',
                    'password' => Hash::make('secret'),
                    'first_name' => $facData['code'],
                    'last_name' => 'Admin',
                    'display_name' => "{$facData['name']} Admin",
                    'faculty_id' => $faculty->id,
                ]
            );
            if (!$fAdmin->hasRole($facultyAdminRole)) {
                $fAdmin->assignRole($facultyAdminRole);
            }

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

                // 8 Teachers per department
                $teachers = [];
                for ($t = 1; $t <= 8; $t++) {
                    $teacher = Teacher::firstOrCreate(
                        ['email' => strtolower("{$deptData['code']}_teacher_{$t}@university.edu")],
                        [
                            'name' => "Dr. " . fake()->lastName() . " ({$deptData['code']})",
                            'password' => Hash::make('secret'),
                            'phone' => fake()->phoneNumber(),
                            'qualification' => ['PhD', 'MSc', 'Professor'][rand(0, 2)],
                            'campus_id' => $faculty->campus_id,
                        ]
                    );
                    $teacher->faculties()->syncWithoutDetaching([$faculty->id]);
                    $teachers[] = $teacher;
                }

                // 15 Students per department
                $students = [];
                for ($st = 1; $st <= 15; $st++) {
                    $students[] = Student::firstOrCreate(
                        ['email' => strtolower("{$deptData['code']}_student_{$st}@university.edu")],
                        [
                            'name' => fake()->name(),
                            'password' => Hash::make('secret'),
                            'student_id' => "ST-{$deptData['code']}-" . str_pad($st, 5, '0', STR_PAD_LEFT),
                            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
                            'campus_id' => $faculty->campus_id,
                        ]
                    );
                }

                // 8 Subjects per department
                for ($s = 1; $s <= 8; $s++) {
                    $subject = Subject::firstOrCreate(
                        ['department_id' => $department->id, 'code' => "{$deptData['code']}-{$s}01"],
                        [
                            'faculty_id' => $faculty->id,
                            'name' => ['en' => fake()->words(3, true) . " ({$deptData['code']})", 'ar' => 'مادة ' . $s],
                        ]
                    );
                    $curriculum->subjects()->syncWithoutDetaching([$subject->id => ['is_mandatory' => $s <= 5]]);

                    // 2 sections per subject
                    for ($sec = 1; $sec <= 2; $sec++) {
                        $room = $allRooms[array_rand($allRooms)];
                        $primaryTeacher = $teachers[($s + $sec - 2) % count($teachers)];
                        $secondaryTeacher = $teachers[($s + $sec - 1) % count($teachers)];

                        $offering = CourseOffering::firstOrCreate(
                            ['term_id' => $activeTerm->id, 'subject_id' => $subject->id, 'section_number' => str_pad($sec, 2, '0', STR_PAD_LEFT)],
                            ['room_id' => $room->id, 'capacity' => $room->capacity]
                        );

                        // Attach instructors
                        if (!$offering->teachers()->where('teacher_id', $primaryTeacher->id)->exists()) {
                            $offering->teachers()->attach($primaryTeacher->id, ['is_primary' => true]);
                        }
                        if ($primaryTeacher->id !== $secondaryTeacher->id && !$offering->teachers()->where('teacher_id', $secondaryTeacher->id)->exists()) {
                            $offering->teachers()->attach($secondaryTeacher->id, ['is_primary' => false]);
                        }

                        // Schedule: Lecture + Lab per section with assigned teachers
                        $dayIdx = ($s + $sec) % count($days);
                        $slotIdx = ($s - 1) % count($timeSlots);

                        CourseSchedule::firstOrCreate(
                            ['course_offering_id' => $offering->id, 'day' => $days[$dayIdx], 'start_time' => $timeSlots[$slotIdx][0]],
                            [
                                'session_type_id' => $sessionTypes->get('LECT')?->id,
                                'end_time' => $timeSlots[$slotIdx][1],
                                'teacher_id' => $primaryTeacher->id,
                            ]
                        );

                        $labDayIdx = ($dayIdx + 2) % count($days);
                        $labSlotIdx = ($slotIdx + 1) % count($timeSlots);
                        CourseSchedule::firstOrCreate(
                            ['course_offering_id' => $offering->id, 'day' => $days[$labDayIdx], 'start_time' => $timeSlots[$labSlotIdx][0]],
                            [
                                'session_type_id' => $sessionTypes->get('LAB')?->id,
                                'end_time' => $timeSlots[$labSlotIdx][1],
                                'teacher_id' => $secondaryTeacher->id,
                            ]
                        );

                        // Enroll students (half per section)
                        $sectionStudents = $sec === 1
                            ? array_slice($students, 0, (int) ceil(count($students) / 2))
                            : array_slice($students, (int) ceil(count($students) / 2));

                        foreach ($sectionStudents as $student) {
                            CourseEnrollment::firstOrCreate(
                                ['student_id' => $student->id, 'course_offering_id' => $offering->id],
                                ['status' => 'enrolled', 'enrolled_at' => now()]
                            );
                        }
                    }
                }
            }
        }

        $this->command->info('Demo data seeded successfully!');
    }

    private function seedSessionTypes(): void
    {
        $types = [
            ['code' => 'LECT', 'name' => 'Lecture', 'is_active' => true],
            ['code' => 'LAB', 'name' => 'Laboratory', 'is_active' => true],
            ['code' => 'TUT', 'name' => 'Tutorial', 'is_active' => true],
            ['code' => 'SEM', 'name' => 'Seminar', 'is_active' => true],
            ['code' => 'PRAC', 'name' => 'Practical', 'is_active' => true],
        ];

        foreach ($types as $type) {
            SessionType::firstOrCreate(['code' => $type['code']], $type);
        }
    }
}
