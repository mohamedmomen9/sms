<?php

namespace Modules\Students\Services;

use Modules\Subject\Contracts\CourseServiceInterface;
use Modules\Students\Models\Student;
use Modules\Subject\DTOs\CourseDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Exception;

class StudentCourseService implements CourseServiceInterface
{
    protected Student $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function getCurrentCourses(): Collection
    {
        $enrollments = $this->student->currentClasses()
            ->with(['courseOffering.subject', 'courseOffering.teacher', 'courseOffering.room', 'courseOffering.term'])
            ->get();

        return $enrollments->map(function ($enrollment) {
            $offering = $enrollment->courseOffering;
            $subject = $offering->subject;
            
            return new CourseDTO(
                id: $offering->id,
                name: $subject->name,
                code: $subject->code,
                section: $offering->section_number,
                schedule: $offering->schedule_json,
                room: $offering->room->name ?? null,
                teacherName: $offering->teacher->name ?? null,
                termName: $offering->term->name ?? null
            );
        });
    }
}
