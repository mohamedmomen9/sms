<?php

namespace Modules\Academic\Filament\Resources\TermResource\Pages;

use Modules\Academic\Filament\Resources\TermResource;
use Filament\Resources\Pages\EditRecord;

class EditTerm extends EditRecord
{
    protected static string $resource = TermResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
