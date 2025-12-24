<?php

namespace App\Filament\Resources\PermissionResource\Schemas;

use Filament\Forms;

class PermissionForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
        ];
    }
}
