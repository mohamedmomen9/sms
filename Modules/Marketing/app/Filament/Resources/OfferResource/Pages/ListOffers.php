<?php

namespace Modules\Marketing\Filament\Resources\OfferResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Marketing\Filament\Resources\OfferResource;

class ListOffers extends ListRecords
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
