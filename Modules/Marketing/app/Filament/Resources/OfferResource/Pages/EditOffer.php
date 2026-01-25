<?php

namespace Modules\Marketing\Filament\Resources\OfferResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Marketing\Filament\Resources\OfferResource;

class EditOffer extends EditRecord
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
