<?php

namespace Modules\Subject\Filament\Resources\CourseOfferingResource\Pages;

use Modules\Subject\Filament\Resources\CourseOfferingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseOffering extends CreateRecord
{
    protected static string $resource = CourseOfferingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
