<?php

namespace Modules\Academic\Filament\Resources\AcademicYearResource\Pages;

use Modules\Academic\Filament\Resources\AcademicYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAcademicYears extends ManageRecords
{
    protected static string $resource = AcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
