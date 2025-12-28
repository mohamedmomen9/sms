<?php

namespace App\Filament\Resources\PermissionResource\Schemas;

use Filament\Forms\Components\TextInput;

class PermissionForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
        ];
    }
}
