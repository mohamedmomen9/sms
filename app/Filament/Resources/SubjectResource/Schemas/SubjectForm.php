<?php

namespace App\Filament\Resources\SubjectResource\Schemas;

use Filament\Forms;

use App\Filament\Forms\Components\TranslatableInput;
use Filament\Forms\Components\TextInput;

class SubjectForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\Select::make('department_id')
                ->relationship('department', 'name')
                ->required(),
            Forms\Components\TextInput::make('curriculum')
                ->label('Curriculum / Group')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(255),
            
            TranslatableInput::make('name', TextInput::class, function ($field, $locale) {
                return $field->required()->maxLength(255);
            }),

            Forms\Components\TextInput::make('category'),
            Forms\Components\TextInput::make('type'),
            Forms\Components\TextInput::make('max_hours')
                ->numeric()
                ->required(),
        ];
    }
}
