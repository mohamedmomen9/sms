<?php

namespace Modules\Communications\Filament\Resources\NotificationResource\Pages;

use Modules\Communications\Filament\Resources\NotificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
