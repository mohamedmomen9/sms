<?php

namespace Modules\Academic\Services;

use Illuminate\Support\Collection;
use Modules\Academic\Models\Term;
use Modules\Students\Models\Student;
use Modules\Subject\Models\CourseOffering;
use Modules\Subject\Models\CourseSchedule;
use Modules\Teachers\Models\Teacher;

class ScheduleService
{
    /**
     * Get the current active term
     */
    public function getCurrentTerm(): ?Term
    {
        return Term::where('is_active', true)->first();
    }

    /**
     * Get schedule for a student
     */
    public function getStudentSchedule(Student $student, ?Term $term = null): Collection
    {
        $term = $term ?? $this->getCurrentTerm();

        if (!$term) {
            return collect();
        }

        // Get all course schedules for courses the student is enrolled in for the current term
        $schedules = CourseSchedule::whereHas('courseOffering', function ($query) use ($student, $term) {
            $query->where('term_id', $term->id)
                  ->whereHas('enrollments', function ($q) use ($student) {
                      $q->where('student_id', $student->id);
                  });
        })
        ->with([
            'courseOffering.subject',
            'courseOffering.teacher',
            'courseOffering.room.building.campus',
            'courseOffering.term',
        ])
        ->ordered()
        ->get();

        return $this->formatScheduleItems($schedules, false);
    }

    /**
     * Get schedule for a teacher
     */
    public function getTeacherSchedule(Teacher $teacher, ?Term $term = null): Collection
    {
        $term = $term ?? $this->getCurrentTerm();

        if (!$term) {
            return collect();
        }

        // Get all course schedules for courses the teacher is teaching for the current term
        $schedules = CourseSchedule::whereHas('courseOffering', function ($query) use ($teacher, $term) {
            $query->where('term_id', $term->id)
                  ->where('teacher_id', $teacher->id);
        })
        ->with([
            'courseOffering.subject',
            'courseOffering.room.building.campus',
            'courseOffering.term',
            'courseOffering.enrollments',
        ])
        ->ordered()
        ->get();

        return $this->formatScheduleItems($schedules, true);
    }

    /**
     * Format schedule items from CourseSchedule models
     */
    protected function formatScheduleItems(Collection $schedules, bool $includeEnrollmentCount = false): Collection
    {
        return $schedules->map(function (CourseSchedule $schedule) use ($includeEnrollmentCount) {
            $offering = $schedule->courseOffering;
            $room = $offering->room;
            $building = $room?->building;
            $campus = $building?->campus;

            $item = [
                'schedule_id' => $schedule->id,
                'course_offering_id' => $offering->id,
                'subject' => [
                    'id' => $offering->subject->id,
                    'code' => $offering->subject->code,
                    'name' => $offering->subject->name,
                ],
                'section_number' => $offering->section_number,
                'day' => $schedule->day,
                'day_order' => $schedule->day_order,
                'start_time' => $schedule->start_time?->format('H:i:s'),
                'end_time' => $schedule->end_time?->format('H:i:s'),
                'location' => [
                    'campus' => $campus?->name,
                    'building' => [
                        'name' => $building?->name,
                        'code' => $building?->code,
                    ],
                    'room' => [
                        'number' => $room?->number,
                        'name' => $room?->name,
                        'floor' => $room?->floor_number,
                        'label' => $room?->label_name,
                    ],
                ],
                'term' => [
                    'id' => $offering->term->id,
                    'name' => $offering->term->name,
                ],
            ];

            // Add instructor info for student view
            if (!$includeEnrollmentCount && $offering->teacher) {
                $item['instructor'] = [
                    'id' => $offering->teacher->id,
                    'name' => $offering->teacher->name,
                    'email' => $offering->teacher->email,
                ];
            }

            // Add enrollment count for teacher view
            if ($includeEnrollmentCount) {
                $item['enrollment_count'] = $offering->enrollments->count();
                $item['capacity'] = $offering->capacity;
            }

            return $item;
        });
    }

    /**
     * Group schedule by day
     */
    public function groupScheduleByDay(Collection $schedule): Collection
    {
        return $schedule->groupBy('day')->map(function ($items, $day) {
            return [
                'day' => $day,
                'day_order' => CourseSchedule::DAY_ORDER[$day] ?? 99,
                'classes' => $items->sortBy('start_time')->values()->toArray(),
            ];
        })->sortBy('day_order')->values();
    }

    /**
     * Get today's schedule for a student
     */
    public function getStudentTodaySchedule(Student $student): Collection
    {
        $schedule = $this->getStudentSchedule($student);
        $today = now()->format('l'); // e.g., "Monday"

        return $schedule->filter(function ($item) use ($today) {
            return ($item['day'] ?? '') === $today;
        })->values();
    }

    /**
     * Get today's schedule for a teacher
     */
    public function getTeacherTodaySchedule(Teacher $teacher): Collection
    {
        $schedule = $this->getTeacherSchedule($teacher);
        $today = now()->format('l'); // e.g., "Monday"

        return $schedule->filter(function ($item) use ($today) {
            return ($item['day'] ?? '') === $today;
        })->values();
    }
}
