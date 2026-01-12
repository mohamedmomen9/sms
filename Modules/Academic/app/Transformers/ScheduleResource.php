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
            'course_offering_id' => $this['course_offering_id'],
            'subject' => $this['subject'],
            'section_number' => $this['section_number'],
            'day' => $this['day'],
            'start_time' => $this['start_time'],
            'end_time' => $this['end_time'],
            'location' => $this['location'],
            'term' => $this['term'],
            'instructor' => $this['instructor'] ?? null,
            'enrollment_count' => $this['enrollment_count'] ?? null,
            'capacity' => $this['capacity'] ?? null,
        ];
    }
}
