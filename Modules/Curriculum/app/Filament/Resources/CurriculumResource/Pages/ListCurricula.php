<?php

namespace Modules\Curriculum\Filament\Resources\CurriculumResource\Pages;

use Modules\Curriculum\Filament\Resources\CurriculumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCurricula extends ListRecords
{
    protected static string $resource = CurriculumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
