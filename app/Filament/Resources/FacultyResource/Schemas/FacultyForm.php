<?php

namespace App\Filament\Resources\FacultyResource\Schemas;

use Filament\Forms;

class FacultyForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\Select::make('university_id')
                ->relationship('university', 'name')
                ->required(),
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
        ];
    }
}
