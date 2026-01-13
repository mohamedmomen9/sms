<?php

namespace Modules\Subject\Filament\Resources\SessionTypeResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Subject\Filament\Resources\SessionTypeResource;

class EditSessionType extends EditRecord
{
    protected static string $resource = SessionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
