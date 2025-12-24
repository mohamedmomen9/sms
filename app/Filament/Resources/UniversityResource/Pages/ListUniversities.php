<?php

namespace App\Filament\Admin\Resources\UniversityResource\Pages;

use App\Filament\Admin\Resources\UniversityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUniversities extends ListRecords
{
    protected static string $resource = UniversityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
