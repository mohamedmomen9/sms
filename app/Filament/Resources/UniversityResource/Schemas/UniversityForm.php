<?php

namespace App\Filament\Resources\UniversityResource\Schemas;

use Filament\Forms;

class UniversityForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('logo')
                ->image()
                ->directory('university-logos'),
        ];
    }
}
