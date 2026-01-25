<?php

namespace Modules\Family\Filament\Resources\GuardianResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Family\Filament\Resources\GuardianResource;

class EditGuardian extends EditRecord
{
    protected static string $resource = GuardianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
