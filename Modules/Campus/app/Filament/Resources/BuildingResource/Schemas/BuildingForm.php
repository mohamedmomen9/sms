<?php

namespace Modules\Campus\Filament\Resources\BuildingResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BuildingForm
{
    public static function schema(): array
    {
        return [
            Select::make('campus_id')
                ->relationship('campus', 'name')
                ->required()
                ->searchable()
                ->preload(),
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('code')
                ->required()
                ->maxLength(255),
            TextInput::make('location_coordinates')
                ->maxLength(255),
        ];
    }
}
