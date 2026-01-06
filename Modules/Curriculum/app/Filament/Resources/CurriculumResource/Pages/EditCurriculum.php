<?php

namespace Modules\Curriculum\Filament\Resources\CurriculumResource\Pages;

use Modules\Curriculum\Filament\Resources\CurriculumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCurriculum extends EditRecord
{
    protected static string $resource = CurriculumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
