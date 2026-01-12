<?php

namespace Modules\Academic\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ScheduleCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ScheduleResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'schedule' => $this->collection,
            'meta' => [
                'total_classes' => $this->collection->count(),
                'term' => $this->collection->first()['term'] ?? null,
            ],
        ];
    }
}
