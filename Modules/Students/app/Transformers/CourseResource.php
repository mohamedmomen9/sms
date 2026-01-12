<?php

namespace Modules\Students\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // $this is the CourseEnrollment instance, loading 'courseOffering' and its relations
        $offering = $this->courseOffering;
        $subject = $offering->subject;
        $teacher = $offering->teacher;
        $room = $offering->room;

        return [
            'enrollment_id' => $this->id,
            'status' => $this->status,
            'grade' => $this->grade,
            'course' => [
                'id' => $offering->id,
                'name' => $subject->name ?? null,
                'code' => $subject->code ?? null,
                'credits' => $subject->credits ?? null,
                'section' => $offering->section_number,
                'schedule' => $offering->schedule_json,
                'room' => $room ? $room->name : null, // Assuming Room has a 'name' field
            ],
            'teacher' => [
                'id' => $teacher->id ?? null,
                'name' => $teacher->name ?? null,
            ],
            'term' => [
                'id' => $offering->term_id,
                'name' => $offering->term->name ?? null,
            ]
        ];
    }
}
