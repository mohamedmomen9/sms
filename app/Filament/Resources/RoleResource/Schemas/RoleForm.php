<?php

namespace App\Filament\Resources\RoleResource\Schemas;

use Filament\Forms\Components\TextInput;
use App\Filament\Forms\Components\PermissionGroup;

class RoleForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            PermissionGroup::make('permissions')
                ->relationship('permissions', 'name')
                ->columnSpanFull(),
        ];
    }
}
