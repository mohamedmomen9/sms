<?php

namespace Modules\Marketing\Filament\Resources\OfferLogResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Marketing\Filament\Resources\OfferLogResource;

class ListOfferLogs extends ListRecords
{
    protected static string $resource = OfferLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
