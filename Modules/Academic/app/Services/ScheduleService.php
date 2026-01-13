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
    public function getCurrentTerm(): ?Term
    {
        return Term::where('is_active', true)->first();
    }

    public function getStudentSchedule(Student $student, ?Term $term = null): Collection
    {
        $term = $term ?? $this->getCurrentTerm();

        if (!$term) {
            return collect();
        }

        $schedules = CourseSchedule::whereHas('courseOffering', function ($query) use ($student, $term) {
            $query->where('term_id', $term->id)
                  ->whereHas('enrollments', function ($q) use ($student) {
                      $q->where('student_id', $student->id);
                  });
        })
        ->with([
            'courseOffering.subject',
            'courseOffering.teachers',
            'courseOffering.room.building.campus',
            'courseOffering.term',
            'sessionType',
            'teacher',
        ])
        ->ordered()
        ->get();

        return $this->formatScheduleItems($schedules, false);
    }

    public function getTeacherSchedule(Teacher $teacher, ?Term $term = null): Collection
    {
        $term = $term ?? $this->getCurrentTerm();

        if (!$term) {
            return collect();
        }

        // Include sessions directly assigned OR unassigned (teacher is on offering's instructor list)
        $schedules = CourseSchedule::whereHas('courseOffering', function ($query) use ($teacher, $term) {
            $query->where('term_id', $term->id)
                  ->whereHas('teachers', fn ($tq) => $tq->where('teachers.id', $teacher->id));
        })
        ->where(function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)
                  ->orWhereNull('teacher_id');
        })
        ->with([
            'courseOffering.subject',
            'courseOffering.room.building.campus',
            'courseOffering.term',
            'courseOffering.enrollments',
            'courseOffering.teachers',
            'sessionType',
            'teacher',
        ])
        ->ordered()
        ->get();

        return $this->formatScheduleItems($schedules, true);
    }

    protected function formatScheduleItems(Collection $schedules, bool $includeEnrollmentCount = false): Collection
    {
        return $schedules->map(function (CourseSchedule $schedule) use ($includeEnrollmentCount) {
            $offering = $schedule->courseOffering;
            $room = $offering->room;
            $building = $room?->building;
            $campus = $building?->campus;

            // Prefer session-specific instructor, fallback to primary from offering
            $instructor = $schedule->teacher ?? $offering->primaryInstructor();

            $item = [
                'schedule_id' => $schedule->id,
                'course_offering_id' => $offering->id,
                'subject_id' => $offering->subject->id,
                'subject_code' => $offering->subject->code,
                'subject_name' => $offering->subject->name,
                'section_number' => $offering->section_number,
                'day' => $schedule->day,
                'day_order' => $schedule->day_order,
                'start_time' => $schedule->start_time?->format('H:i'),
                'end_time' => $schedule->end_time?->format('H:i'),
                'session_type_code' => $schedule->sessionType?->code,
                'session_type_name' => $schedule->sessionType?->name,
                'campus' => $campus?->name,
                'building_name' => $building?->name,
                'building_code' => $building?->code,
                'room_number' => $room?->number,
                'room_name' => $room?->name,
                'room_label' => $room?->label_name,
                'term_id' => $offering->term->id,
                'term_name' => $offering->term->name,
                'instructor_id' => $instructor?->id,
                'instructor_name' => $instructor?->name,
                'instructor_email' => $instructor?->email,
            ];

            if ($includeEnrollmentCount) {
                $item['enrollment_count'] = $offering->enrollments->count();
                $item['capacity'] = $offering->capacity;
            }

            return $item;
        });
    }

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

    public function groupScheduleByCourse(Collection $schedule): Collection
    {
        return $schedule->groupBy('subject_code')->map(function ($items, $code) {
            $first = $items->first();
            return [
                'subject_id' => $first['subject_id'],
                'subject_code' => $code,
                'subject_name' => $first['subject_name'],
                'section_number' => $first['section_number'],
                'course_offering_id' => $first['course_offering_id'],
                'sessions' => $items->map(fn ($item) => [
                    'schedule_id' => $item['schedule_id'],
                    'day' => $item['day'],
                    'day_order' => $item['day_order'],
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'session_type_code' => $item['session_type_code'],
                    'session_type_name' => $item['session_type_name'],
                    'room_label' => $item['room_label'],
                    'instructor_id' => $item['instructor_id'],
                    'instructor_name' => $item['instructor_name'],
                    'instructor_email' => $item['instructor_email'],
                ])->sortBy('day_order')->values()->toArray(),
            ];
        })->sortBy('subject_code')->values();
    }

    public function getStudentTodaySchedule(Student $student): Collection
    {
        $schedule = $this->getStudentSchedule($student);
        $today = now()->format('l'); // e.g., "Monday"

        return $schedule->filter(function ($item) use ($today) {
            return ($item['day'] ?? '') === $today;
        })->values();
    }

    public function getTeacherTodaySchedule(Teacher $teacher): Collection
    {
        $schedule = $this->getTeacherSchedule($teacher);
        $today = now()->format('l'); // e.g., "Monday"

        return $schedule->filter(function ($item) use ($today) {
            return ($item['day'] ?? '') === $today;
        })->values();
    }
}

