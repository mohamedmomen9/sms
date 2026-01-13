<?php

namespace Modules\Subject\Filament\Resources\SessionTypeResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Subject\Filament\Resources\SessionTypeResource;

class ListSessionTypes extends ListRecords
{
    protected static string $resource = SessionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
