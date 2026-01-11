<?php

namespace Modules\Subject\Filament\Resources\CourseOfferingResource\Pages;

use Modules\Subject\Filament\Resources\CourseOfferingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseOfferings extends ListRecords
{
    protected static string $resource = CourseOfferingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
