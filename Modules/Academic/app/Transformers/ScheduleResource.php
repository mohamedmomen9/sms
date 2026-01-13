<?php

namespace Modules\Academic\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'schedule_id' => $this['schedule_id'],
            'course_offering_id' => $this['course_offering_id'],
            'subject_id' => $this['subject_id'],
            'subject_code' => $this['subject_code'],
            'subject_name' => $this['subject_name'],
            'section_number' => $this['section_number'],
            'day' => $this['day'],
            'day_order' => $this['day_order'],
            'start_time' => $this['start_time'],
            'end_time' => $this['end_time'],
            'session_type_code' => $this['session_type_code'] ?? null,
            'session_type_name' => $this['session_type_name'] ?? null,
            'campus' => $this['campus'] ?? null,
            'building_name' => $this['building_name'] ?? null,
            'building_code' => $this['building_code'] ?? null,
            'room_number' => $this['room_number'] ?? null,
            'room_name' => $this['room_name'] ?? null,
            'room_label' => $this['room_label'] ?? null,
            'term_id' => $this['term_id'],
            'term_name' => $this['term_name'],
            'instructor_id' => $this['instructor_id'] ?? null,
            'instructor_name' => $this['instructor_name'] ?? null,
            'instructor_email' => $this['instructor_email'] ?? null,
            'enrollment_count' => $this['enrollment_count'] ?? null,
            'capacity' => $this['capacity'] ?? null,
        ];
    }
}


