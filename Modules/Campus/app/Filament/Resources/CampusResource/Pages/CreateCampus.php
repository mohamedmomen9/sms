<?php

namespace Modules\Campus\Filament\Resources\CampusResource\Pages;

use Modules\Campus\Filament\Resources\CampusResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCampus extends CreateRecord
{
    protected static string $resource = CampusResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
