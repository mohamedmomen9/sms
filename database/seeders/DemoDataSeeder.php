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
use Modules\Services\Models\ServiceType;
use Modules\Services\Models\ServiceRequest;
use Modules\Services\Models\Appointment;
use Modules\Services\Models\AppointmentDepartment;
use Modules\Services\Models\AppointmentPurpose;
use Modules\Services\Models\AppointmentSlot;
use Modules\Payment\Models\PaymentRegistration;
use Modules\Training\Models\TrainingOpportunity;
use Modules\Services\Database\Factories\ServiceTypeFactory;

/**
 * Demo data seeder with realistic, curated names for staging/demo environments.
 *
 * Name sources:
 * - Teacher surnames: Common academic surnames representing diverse backgrounds
 * - Student names: Realistic first/last name combinations from various cultures
 * - Subject names: Department-specific course titles following academic conventions
 *
 * All names are hardcoded for consistency across seeder runs and demo appropriateness.
 */
class DemoDataSeeder extends Seeder
{
    /**
     * Curated list of realistic teacher surnames.
     * Diverse selection representing common academic surnames from various backgrounds.
     */
    private const TEACHER_SURNAMES = [
        'Anderson',
        'Chen',
        'Williams',
        'Martinez',
        'Thompson',
        'Patel',
        'Johnson',
        'Kim',
        'Garcia',
        'Brown',
        'Wilson',
        'Taylor',
        'Lee',
        'Moore',
        'Jackson',
        'White',
        'Harris',
        'Clark',
        'Lewis',
        'Robinson',
        'Walker',
        'Young',
        'Allen',
        'King',
        'Wright',
        'Scott',
        'Green',
        'Baker',
        'Adams',
        'Nelson',
        'Hill',
        'Ramirez',
        'Campbell',
        'Mitchell',
        'Roberts',
        'Carter',
        'Phillips',
        'Evans',
        'Turner',
        'Torres',
        'Parker',
        'Collins',
        'Edwards',
        'Stewart',
        'Flores',
        'Morris',
        'Nguyen',
        'Murphy',
        'Rivera',
        'Cook',
    ];

    /**
     * Curated list of realistic student first names.
     * Gender-neutral and diverse selection for inclusive demos.
     */
    private const STUDENT_FIRST_NAMES = [
        'James',
        'Emma',
        'Oliver',
        'Sophia',
        'William',
        'Ava',
        'Benjamin',
        'Isabella',
        'Lucas',
        'Mia',
        'Henry',
        'Charlotte',
        'Alexander',
        'Amelia',
        'Sebastian',
        'Harper',
        'Jack',
        'Evelyn',
        'Aiden',
        'Abigail',
        'Owen',
        'Emily',
        'Samuel',
        'Elizabeth',
        'Ryan',
        'Sofia',
        'Nathan',
        'Avery',
        'Leo',
        'Ella',
        'Adam',
        'Scarlett',
        'Daniel',
        'Grace',
        'Matthew',
        'Chloe',
        'Joseph',
        'Victoria',
        'David',
        'Riley',
        'Andrew',
        'Aria',
        'Ethan',
        'Lily',
        'Michael',
        'Zoey',
        'Noah',
        'Hannah',
        'Liam',
        'Nora',
        'Omar',
        'Fatima',
        'Ahmed',
        'Layla',
        'Yusuf',
        'Sara',
        'Ali',
        'Nadia',
        'Hassan',
        'Mariam',
        'Wei',
        'Mei',
        'Jin',
        'Yuki',
        'Hiroshi',
        'Sakura',
        'Raj',
        'Priya',
        'Arun',
        'Ananya',
        'Carlos',
        'Maria',
        'Diego',
        'Ana',
        'Luis',
    ];

    /**
     * Curated list of realistic student last names.
     * Diverse selection representing various cultural backgrounds.
     */
    private const STUDENT_LAST_NAMES = [
        'Smith',
        'Johnson',
        'Williams',
        'Brown',
        'Jones',
        'Garcia',
        'Miller',
        'Davis',
        'Rodriguez',
        'Martinez',
        'Hernandez',
        'Lopez',
        'Gonzalez',
        'Wilson',
        'Anderson',
        'Thomas',
        'Taylor',
        'Moore',
        'Jackson',
        'Martin',
        'Lee',
        'Perez',
        'Thompson',
        'White',
        'Harris',
        'Sanchez',
        'Clark',
        'Ramirez',
        'Lewis',
        'Robinson',
        'Walker',
        'Young',
        'Allen',
        'King',
        'Wright',
        'Scott',
        'Torres',
        'Nguyen',
        'Hill',
        'Flores',
        'Green',
        'Adams',
        'Nelson',
        'Baker',
        'Hall',
        'Rivera',
        'Campbell',
        'Mitchell',
        'Carter',
        'Roberts',
        'Ahmed',
        'Hassan',
        'Ali',
        'Khan',
        'Ibrahim',
        'Chen',
        'Wang',
        'Li',
        'Zhang',
        'Liu',
        'Patel',
        'Shah',
        'Kumar',
        'Singh',
        'Sharma',
        'Tanaka',
        'Yamamoto',
        'Suzuki',
        'Kim',
        'Park',
        'Santos',
        'Oliveira',
        'Silva',
        'Costa',
        'Ferreira',
    ];

    /**
     * Department-specific subject names.
     * Each department has 8+ realistic course titles following academic conventions.
     */
    private const SUBJECT_NAMES = [
        'CS' => [
            'Introduction to Programming',
            'Data Structures and Algorithms',
            'Database Management Systems',
            'Computer Networks',
            'Software Engineering',
            'Operating Systems',
            'Artificial Intelligence',
            'Web Development',
            'Computer Architecture',
            'Cybersecurity Fundamentals',
        ],
        'ME' => [
            'Engineering Mechanics',
            'Thermodynamics',
            'Fluid Mechanics',
            'Machine Design',
            'Manufacturing Processes',
            'Heat Transfer',
            'Control Systems',
            'Materials Science',
            'CAD/CAM Systems',
            'Robotics Engineering',
        ],
        'EE' => [
            'Circuit Analysis',
            'Digital Electronics',
            'Electromagnetic Theory',
            'Power Systems',
            'Signal Processing',
            'Control Engineering',
            'Microprocessors',
            'Communication Systems',
            'VLSI Design',
            'Renewable Energy Systems',
        ],
        'PHY' => [
            'Classical Mechanics',
            'Electromagnetism',
            'Quantum Physics',
            'Thermodynamics and Statistical Mechanics',
            'Optics',
            'Nuclear Physics',
            'Solid State Physics',
            'Astrophysics',
            'Mathematical Physics',
            'Experimental Physics',
        ],
        'MATH' => [
            'Calculus I',
            'Linear Algebra',
            'Differential Equations',
            'Probability and Statistics',
            'Abstract Algebra',
            'Real Analysis',
            'Complex Analysis',
            'Numerical Methods',
            'Discrete Mathematics',
            'Topology',
        ],
        'CHEM' => [
            'General Chemistry',
            'Organic Chemistry',
            'Inorganic Chemistry',
            'Physical Chemistry',
            'Analytical Chemistry',
            'Biochemistry',
            'Environmental Chemistry',
            'Polymer Chemistry',
            'Spectroscopy',
            'Chemical Kinetics',
        ],
        'GM' => [
            'Human Anatomy',
            'Physiology',
            'Biochemistry for Medicine',
            'Pathology',
            'Pharmacology',
            'Microbiology',
            'Clinical Diagnosis',
            'Internal Medicine',
            'Pediatrics',
            'Emergency Medicine',
        ],
        'SURG' => [
            'Surgical Anatomy',
            'General Surgery',
            'Anesthesiology',
            'Orthopedic Surgery',
            'Neurosurgery',
            'Cardiovascular Surgery',
            'Plastic Surgery',
            'Trauma Surgery',
            'Minimally Invasive Surgery',
            'Surgical Oncology',
        ],
        'ACC' => [
            'Financial Accounting',
            'Managerial Accounting',
            'Cost Accounting',
            'Auditing',
            'Taxation',
            'Accounting Information Systems',
            'Corporate Finance',
            'International Accounting',
            'Forensic Accounting',
            'Government Accounting',
        ],
        'MKT' => [
            'Principles of Marketing',
            'Consumer Behavior',
            'Digital Marketing',
            'Brand Management',
            'Marketing Research',
            'Advertising and Promotion',
            'Sales Management',
            'International Marketing',
            'Social Media Marketing',
            'Retail Marketing',
        ],
        'FIN' => [
            'Corporate Finance',
            'Investment Analysis',
            'Financial Markets',
            'Portfolio Management',
            'Risk Management',
            'International Finance',
            'Financial Modeling',
            'Derivatives and Options',
            'Banking and Financial Institutions',
            'Behavioral Finance',
        ],
        'FA' => [
            'Drawing Fundamentals',
            'Color Theory',
            'Painting Techniques',
            'Sculpture',
            'Art History',
            'Digital Art',
            'Printmaking',
            'Mixed Media',
            'Contemporary Art',
            'Art Criticism',
        ],
        'MUS' => [
            'Music Theory',
            'Ear Training',
            'Music History',
            'Composition',
            'Orchestration',
            'Music Performance',
            'Conducting',
            'Ethnomusicology',
            'Music Technology',
            'Jazz Studies',
        ],
        'PL' => [
            'Constitutional Law',
            'Administrative Law',
            'Criminal Law',
            'International Law',
            'Human Rights Law',
            'Environmental Law',
            'Tax Law',
            'Labor Law',
            'Immigration Law',
            'Public Policy Law',
        ],
        'PRL' => [
            'Contract Law',
            'Property Law',
            'Tort Law',
            'Corporate Law',
            'Family Law',
            'Intellectual Property',
            'Commercial Law',
            'Banking Law',
            'Insurance Law',
            'Consumer Protection Law',
        ],
    ];

    /**
     * Phone number patterns for realistic phone generation.
     * Uses Egyptian mobile phone format.
     */
    private const PHONE_PREFIXES = ['010', '011', '012', '015'];

    /**
     * Counters for deterministic name assignment.
     */
    private int $teacherNameIndex = 0;
    private int $studentNameIndex = 0;

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
                    $roomTypes = ['classroom', 'lab', 'auditorium', 'classroom'];
                    $room = Room::firstOrCreate(
                        ['room_code' => "{$building->code}-{$b}0{$r}"],
                        [
                            'building_id' => $building->id,
                            'number' => "{$b}0{$r}",
                            'name' => "Room {$b}0{$r}",
                            'floor_number' => $b,
                            'type' => $roomTypes[($r - 1) % count($roomTypes)],
                            'capacity' => 30 + (($r * 5) % 31), // Deterministic capacity: 30-60
                            'status' => 'active',
                        ]
                    );
                    $room->facilities()->syncWithoutDetaching(
                        collect($facilities)->take(2 + ($r % 3))->pluck('id')->toArray()
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
            ['08:00', '09:30'],
            ['10:00', '11:30'],
            ['12:00', '13:30'],
            ['14:00', '15:30'],
            ['16:00', '17:30'],
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

                // 8 Teachers per department using realistic names
                $teachers = [];
                for ($t = 1; $t <= 8; $t++) {
                    $teacherName = $this->getTeacherName($deptData['code']);
                    $teacher = Teacher::firstOrCreate(
                        ['email' => strtolower("{$deptData['code']}_teacher_{$t}@university.edu")],
                        [
                            'name' => $teacherName,
                            'password' => Hash::make('secret'),
                            'phone' => $this->generatePhone($t),
                            'qualification' => ['PhD', 'MSc', 'Professor'][($t - 1) % 3],
                            'campus_id' => $faculty->campus_id,
                        ]
                    );
                    $teacher->faculties()->syncWithoutDetaching([$faculty->id]);
                    $teachers[] = $teacher;
                }

                // 15 Students per department using realistic names
                $students = [];
                for ($st = 1; $st <= 15; $st++) {
                    $studentName = $this->getStudentName();
                    $students[] = Student::firstOrCreate(
                        ['email' => strtolower("{$deptData['code']}_student_{$st}@university.edu")],
                        [
                            'name' => $studentName,
                            'password' => Hash::make('secret'),
                            'student_id' => "ST-{$deptData['code']}-" . str_pad($st, 5, '0', STR_PAD_LEFT),
                            'date_of_birth' => $this->generateBirthDate($st),
                            'campus_id' => $faculty->campus_id,
                        ]
                    );
                }

                // 8 Subjects per department using department-specific names
                for ($s = 1; $s <= 8; $s++) {
                    $subjectName = $this->getSubjectName($deptData['code'], $s);
                    $subject = Subject::firstOrCreate(
                        ['department_id' => $department->id, 'code' => "{$deptData['code']}-{$s}01"],
                        [
                            'faculty_id' => $faculty->id,
                            'name' => ['en' => $subjectName, 'ar' => 'مادة ' . $s],
                        ]
                    );
                    $curriculum->subjects()->syncWithoutDetaching([$subject->id => ['is_mandatory' => $s <= 5]]);

                    // 2 sections per subject
                    for ($sec = 1; $sec <= 2; $sec++) {
                        $roomIndex = (($s - 1) * 2 + $sec - 1) % count($allRooms);
                        $room = $allRooms[$roomIndex];
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

        // Seed additional features (Services, Appointments, Training)
        $this->seedAdditionalFeatures($students, $activeTerm, $facultiesData);

        $this->command->info('Demo data seeded successfully!');
    }

    private function seedAdditionalFeatures(array $students, Term $term, array $facultiesData): void
    {
        // 1. Service Types
        $types = [
            ['name' => 'Official Transcript', 'code' => 'DOC-001', 'price' => 50.00, 'days' => 3],
            ['name' => 'Student ID Replacement', 'code' => 'ID-002', 'price' => 100.00, 'days' => 7],
            ['name' => 'Graduation Certificate', 'code' => 'DOC-003', 'price' => 200.00, 'days' => 14],
        ];

        $serviceTypes = [];
        foreach ($types as $t) {
            $serviceTypes[] = ServiceType::firstOrCreate(['code' => $t['code']], [
                'name' => $t['name'],
                'price' => $t['price'],
                'duration_days' => $t['days'],
                'is_active' => true,
                'description' => $t['name'] . ' for students.',
                'requires_shipping' => $t['code'] !== 'ID-002',
            ]);
        }

        // 2. Service Requests & Payments
        foreach ($students as $index => $student) {
            if ($index % 3 === 0) { // 33% of students
                $type = $serviceTypes[array_rand($serviceTypes)];

                $request = ServiceRequest::firstOrCreate([
                    'student_id' => $student->student_id,
                    'term_id' => $term->id,
                    'service_type_id' => $type->id,
                ], [
                    'payment_amount' => $type->price,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                ]);

                // 80% Pay
                if (rand(0, 100) < 80) {
                    PaymentRegistration::firstOrCreate([
                        'service_request_id' => $request->id,
                    ], [
                        'student_id' => $student->student_id,
                        'amount' => $request->payment_amount,
                        'payment_method' => 'credit_card',
                        'callback_status' => 'success',
                        'payment_date' => now(),
                        'transaction_id' => 'TXN-' . uniqid(),
                    ]);
                    $request->update(['payment_status' => 'paid', 'status' => 'processing']);
                }
            }
        }

        // 3. Appointments
        $depts = [
            ['name' => 'Academic Advising', 'purposes' => ['Course Selection', 'Career Advice', 'Probation']],
            ['name' => 'Financial Aid', 'purposes' => ['Scholarship Inquiry', 'Tuition Payment', 'Loan Application']],
            ['name' => 'Student Affairs', 'purposes' => ['Housing', 'Clubs', 'Complaints']],
        ];

        $appDepts = [];
        foreach ($depts as $d) {
            $dept = AppointmentDepartment::firstOrCreate(['name' => $d['name']], ['is_active' => true]);
            $appDepts[] = $dept;
            foreach ($d['purposes'] as $p) {
                AppointmentPurpose::firstOrCreate(['name' => $p, 'department_id' => $dept->id], ['is_active' => true]);
            }
        }

        // Slots
        $slots = [];
        $startTime = \Carbon\Carbon::parse('2025-09-01 09:00:00');
        // Generate standard daily slots (9 AM to 5 PM)
        for ($i = 0; $i < 16; $i++) { // 16 half-hour slots = 8 hours
            $start = $startTime->copy()->addMinutes($i * 30);
            $end = $start->copy()->addMinutes(30);

            try {
                $slots[] = AppointmentSlot::firstOrCreate([
                    'start_time' => $start->format('H:i'),
                    'end_time' => $end->format('H:i'),
                ], [
                    'label' => $start->format('g:i A') . ' - ' . $end->format('g:i A'),
                    'is_active' => true,
                    'capacity' => 5
                ]);
            } catch (\Exception $e) {
                // If race condition or weird error, try to fetch existing
                $slots[] = AppointmentSlot::where('start_time', $start->format('H:i'))
                    ->where('end_time', $end->format('H:i'))
                    ->first();
            }
        }
        $slots = array_filter($slots); // Remove nulls if any

        // Book Appointments
        foreach ($students as $index => $student) {
            if ($index % 5 === 0 && count($slots) > 0) { // 20%
                $dept = $appDepts[array_rand($appDepts)];
                $purpose = $dept->purposes->random();
                $slot = $slots[array_rand($slots)];

                Appointment::firstOrCreate([
                    'student_id' => $student->student_id,
                    'appointment_date' => now()->addDays(rand(1, 10))->format('Y-m-d'), // Use date string for lookup
                    'slot_id' => $slot->id,
                ], [
                    'term_id' => $term->id,
                    'department_id' => $dept->id,
                    'purpose_id' => $purpose->id,
                    'status' => 'booked',
                    'notes' => 'Demo appointment',
                ]);
            }
        }

        // 4. Training Opportunities
        $engFaculty = Faculty::where('code', 'ENG')->first();
        if ($engFaculty) {
            TrainingOpportunity::create([
                'organization_name' => 'Tech Corp',
                'description' => 'Summer Internship',
                'faculty_id' => $engFaculty->id,
                'department_id' => $engFaculty->departments->first()->id,
                'concentration' => 'Software',
                'cohort' => '2025',
                'capacity' => 10,
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(5),
                'is_available' => true,
                'conditions' => ['GPA > 3.0'],
                'required_documents' => ['CV', 'Transcript'],
            ]);
        }
    }

    /**
     * Generate a realistic teacher name with academic title.
     * Cycles through surnames deterministically for consistency.
     */
    private function getTeacherName(string $deptCode): string
    {
        $surname = self::TEACHER_SURNAMES[$this->teacherNameIndex % count(self::TEACHER_SURNAMES)];
        $this->teacherNameIndex++;

        return "Dr. {$surname} ({$deptCode})";
    }

    /**
     * Generate a realistic student full name.
     * Combines first and last names deterministically.
     */
    private function getStudentName(): string
    {
        $firstNameIndex = $this->studentNameIndex % count(self::STUDENT_FIRST_NAMES);
        $lastNameIndex = ($this->studentNameIndex / count(self::STUDENT_FIRST_NAMES)) % count(self::STUDENT_LAST_NAMES);

        $firstName = self::STUDENT_FIRST_NAMES[$firstNameIndex];
        $lastName = self::STUDENT_LAST_NAMES[(int) $lastNameIndex];

        $this->studentNameIndex++;

        return "{$firstName} {$lastName}";
    }

    /**
     * Get a subject name from the predefined list for a department.
     * Falls back to a generated name if department has no predefined subjects.
     */
    private function getSubjectName(string $deptCode, int $index): string
    {
        if (isset(self::SUBJECT_NAMES[$deptCode])) {
            $subjects = self::SUBJECT_NAMES[$deptCode];
            $subjectIndex = ($index - 1) % count($subjects);
            return $subjects[$subjectIndex];
        }

        // Fallback for unexpected department codes
        return "Course {$index} ({$deptCode})";
    }

    /**
     * Generate a realistic Egyptian phone number.
     * Uses common mobile prefixes with deterministic suffix.
     */
    private function generatePhone(int $seed): string
    {
        $prefix = self::PHONE_PREFIXES[$seed % count(self::PHONE_PREFIXES)];
        $suffix = str_pad((string) (1000000 + ($seed * 12345) % 9000000), 8, '0', STR_PAD_LEFT);

        return "{$prefix}{$suffix}";
    }

    /**
     * Generate a deterministic birth date for students.
     * Ages range from 18-25 years old.
     */
    private function generateBirthDate(int $seed): string
    {
        $baseYear = 2007; // Makes students 18+ in 2025
        $year = $baseYear - ($seed % 8); // 2007-2000, ages 18-25
        $month = (($seed * 3) % 12) + 1;
        $day = (($seed * 7) % 28) + 1;

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
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
