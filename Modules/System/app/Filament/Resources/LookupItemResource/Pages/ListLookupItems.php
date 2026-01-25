<?php

namespace Modules\System\Filament\Resources\LookupItemResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\System\Filament\Resources\LookupItemResource;

class ListLookupItems extends ListRecords
{
    protected static string $resource = LookupItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
