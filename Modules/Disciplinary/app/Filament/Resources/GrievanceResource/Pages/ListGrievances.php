<?php

namespace Modules\Disciplinary\Filament\Resources\GrievanceResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Disciplinary\Filament\Resources\GrievanceResource;

class ListGrievances extends ListRecords
{
    protected static string $resource = GrievanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
