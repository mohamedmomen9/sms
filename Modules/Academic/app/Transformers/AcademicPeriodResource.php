<?php

namespace Modules\Academic\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AcademicPeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'year' => $this['academic_year'] ? $this['academic_year']['name'] : null,
            'term' => $this['term'] ? $this['term']['name'] : null, // Assuming name is "fall", "spring", etc.
        ];
    }
}
