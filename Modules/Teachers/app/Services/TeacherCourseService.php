<?php

namespace Modules\Teachers\Services;

use Modules\Subject\Contracts\CourseServiceInterface;
use Modules\Teachers\Models\Teacher;
use Modules\Subject\DTOs\CourseDTO;
use Illuminate\Support\Collection;
use Modules\Subject\Models\CourseOffering;

class TeacherCourseService implements CourseServiceInterface
{
    protected Teacher $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function getCurrentCourses(): Collection
    {
        $offerings = CourseOffering::whereHas('teachers', function ($query) {
            $query->where('teachers.id', $this->teacher->id);
        })
            ->whereHas('term', function ($query) {
                $query->where('is_active', true);
            })
            ->with(['subject', 'room', 'term'])
            ->withCount('enrollments')
            ->get();

        return $offerings->map(function ($offering) {
            return new CourseDTO(
                id: $offering->id,
                name: $offering->subject->name,
                code: $offering->subject->code,
                section: $offering->section_number,
                schedule: $offering->schedule_json,
                room: $offering->room->name ?? null,
                teacherName: $this->teacher->name,
                enrollmentCount: $offering->enrollments_count,
                termName: $offering->term->name ?? null
            );
        });
    }
}
