<?php

namespace Modules\Campus\Filament\Resources\FacilityResource\Schemas;

use Filament\Forms\Components\TextInput;

class FacilityForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
        ];
    }
}
