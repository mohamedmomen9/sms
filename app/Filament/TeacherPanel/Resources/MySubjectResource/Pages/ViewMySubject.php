<?php

namespace App\Filament\TeacherPanel\Resources\MySubjectResource\Pages;

use App\Filament\TeacherPanel\Resources\MySubjectResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMySubject extends ViewRecord
{
    protected static string $resource = MySubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
