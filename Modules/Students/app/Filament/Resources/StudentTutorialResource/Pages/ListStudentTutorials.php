<?php

namespace Modules\Students\Filament\Resources\StudentTutorialResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Students\Filament\Resources\StudentTutorialResource;

class ListStudentTutorials extends ListRecords
{
    protected static string $resource = StudentTutorialResource::class;
}
