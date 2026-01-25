<?php

namespace Modules\System\Filament\Resources\LookupItemResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\System\Filament\Resources\LookupItemResource;

class EditLookupItem extends EditRecord
{
    protected static string $resource = LookupItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
