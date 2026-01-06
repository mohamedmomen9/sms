<?php

namespace Modules\Campus\Filament\Resources\CampusResource\Pages;

use Modules\Campus\Filament\Resources\CampusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCampuses extends ListRecords
{
    protected static string $resource = CampusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
