<?php

namespace Modules\Campus\Filament\Resources\BuildingResource\Pages;

use Modules\Campus\Filament\Resources\BuildingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBuildings extends ManageRecords
{
    protected static string $resource = BuildingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
