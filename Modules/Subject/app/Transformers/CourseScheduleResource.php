<?php

namespace Modules\Subject\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseScheduleResource extends JsonResource
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
            'course_offering_id' => $this->course_offering_id,
            'session_type' => $this->when($this->sessionType, [
                'id' => $this->sessionType?->id,
                'code' => $this->sessionType?->code,
                'name' => $this->sessionType?->name,
            ]),
            'day' => $this->day,
            'start_time' => $this->start_time?->format('H:i'),
            'end_time' => $this->end_time?->format('H:i'),
            'time_range' => $this->time_range,
            'label' => $this->label,
        ];
    }
}
