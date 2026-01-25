<?php

namespace Modules\Family\Filament\Resources\GuardianResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Family\Filament\Resources\GuardianResource;

class ListGuardians extends ListRecords
{
    protected static string $resource = GuardianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
