<?php

namespace Modules\Subject\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseOfferingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subject' => [
                'id' => $this->subject->id,
                'code' => $this->subject->code,
                'name' => $this->subject->name,
            ],
            'term' => [
                'id' => $this->term->id,
                'name' => $this->term->name,
            ],
            'section_number' => $this->section_number,
            'capacity' => $this->capacity,
            'room' => $this->when($this->room, [
                'id' => $this->room?->id,
                'code' => $this->room?->room_code,
                'name' => $this->room?->label_name,
            ]),
            'instructors' => $this->teachers->map(fn ($teacher) => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'is_primary' => (bool) $teacher->pivot->is_primary,
            ]),
            'primary_instructor' => $this->when($this->primaryInstructor(), [
                'id' => $this->primaryInstructor()?->id,
                'name' => $this->primaryInstructor()?->name,
            ]),
            'schedules' => CourseScheduleResource::collection($this->whenLoaded('schedules')),
            'enrollments_count' => $this->whenCounted('enrollments'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
