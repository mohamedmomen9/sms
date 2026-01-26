<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Academic\Models\Term;
use Modules\Department\Models\Department;
use Modules\Subject\Models\Subject;
use Modules\Subject\Models\CourseOffering;
use Modules\Subject\Models\CourseSchedule;
use Modules\Subject\Models\SessionType;
use Modules\Students\Models\CourseEnrollment;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;
use Modules\Campus\Models\Room;
use Database\Seeders\DemoDataSeeder;

class DemoCourseSeeder extends Seeder
{
    // Constants for configuration
    public const SUBJECTS_PER_DEPT = 8;
    public const SECTIONS_PER_SUBJECT = 2;

    public function run(): void
    {
        $term = Term::where('is_active', true)->first();
        if (!$term) return;

        $sessionTypes = SessionType::all()->keyBy('code');
        $allRooms = Room::where('status', 'active')->get();
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $timeSlots = [
            ['08:00', '09:30'],
            ['10:00', '11:30'],
            ['12:00', '13:30'],
            ['14:00', '15:30'],
            ['16:00', '17:30'],
        ];

        $departments = Department::with(['faculty', 'curricula'])->get();

        foreach ($departments as $department) {
            $curriculum = $department->curricula->first();
            if (!$curriculum) continue;

            $teachers = Teacher::where('email', 'like', "%{$department->code}_teacher_%")->get();
            $students = Student::where('email', 'like', "%{$department->code}_student_%")->get();

            if ($teachers->isEmpty() || $students->isEmpty() || $allRooms->isEmpty()) continue;

            for ($s = 1; $s <= self::SUBJECTS_PER_DEPT; $s++) {
                $subjectName = $this->getSubjectName($department->code, $s);
                $subject = Subject::firstOrCreate(
                    ['department_id' => $department->id, 'code' => "{$department->code}-{$s}01"],
                    [
                        'faculty_id' => $department->faculty_id,
                        'name' => ['en' => $subjectName, 'ar' => 'مادة ' . $s],
                    ]
                );

                $curriculum->subjects()->syncWithoutDetaching([$subject->id => ['is_mandatory' => $s <= 5]]);

                // Sections
                for ($sec = 1; $sec <= self::SECTIONS_PER_SUBJECT; $sec++) {
                    // Deterministic room assignment
                    $roomIndex = ($department->id + $s + $sec) % $allRooms->count();
                    $room = $allRooms[$roomIndex];

                    $teacherCount = $teachers->count();
                    $t1Index = ($department->id + $s + $sec) % $teacherCount;
                    $primaryTeacher = $teachers[$t1Index];

                    // Select secondary teacher
                    $t2Index = ($t1Index + 1) % $teacherCount;
                    $secondaryTeacher = $teachers[$t2Index];

                    $offering = CourseOffering::firstOrCreate(
                        ['term_id' => $term->id, 'subject_id' => $subject->id, 'section_number' => str_pad($sec, 2, '0', STR_PAD_LEFT)],
                        ['room_id' => $room->id, 'capacity' => $room->capacity]
                    );

                    // Attach primary teacher if missing
                    if (!$offering->teachers()->wherePivot('is_primary', true)->exists()) {
                        $offering->teachers()->attach($primaryTeacher->id, ['is_primary' => true]);
                    }

                    if ($primaryTeacher->id !== $secondaryTeacher->id) {
                        // Attach secondary teacher if missing
                        if (!$offering->teachers()->wherePivot('is_primary', false)->exists()) {
                            $offering->teachers()->attach($secondaryTeacher->id, ['is_primary' => false]);
                        }
                    }

                    // Schedules
                    $dayIdx = ($s + $sec) % count($days);
                    $slotIdx = ($s - 1) % count($timeSlots);

                    // Lecture
                    CourseSchedule::firstOrCreate(
                        ['course_offering_id' => $offering->id, 'day' => $days[$dayIdx], 'start_time' => $timeSlots[$slotIdx][0]],
                        [
                            'session_type_id' => $sessionTypes->get('LECT')?->id,
                            'end_time' => $timeSlots[$slotIdx][1],
                            'teacher_id' => $primaryTeacher->id,
                        ]
                    );

                    // Lab
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

                    // Enrollments (split students between sections)
                    $deptStudents = $students->values();
                    $halfCount = (int) ceil($deptStudents->count() / 2);

                    $sectionStudents = $sec === 1
                        ? $deptStudents->take($halfCount)
                        : $deptStudents->slice($halfCount);

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

    private function getSubjectName(string $deptCode, int $index): string
    {
        $subjects = DemoDataSeeder::SUBJECT_NAMES[$deptCode] ?? [];
        return $subjects[$index - 1] ?? "{$deptCode} Subject {$index}";
    }
}
