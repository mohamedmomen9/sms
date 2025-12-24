<?php

namespace App\Filament\Resources\DepartmentResource\Schemas;

use Filament\Forms;

class DepartmentForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\Select::make('faculty_id')
                ->relationship('faculty', 'name')
                ->required(),
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('status')
                ->required()
                ->maxLength(255),
        ];
    }
}
