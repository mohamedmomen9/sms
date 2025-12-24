<?php

namespace App\Filament\Resources\SubjectResource\Schemas;

use Filament\Forms;

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
            Forms\Components\TextInput::make('name_en')
                ->label('Name (English)')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('name_ar')
                ->label('Name (Arabic)')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('category'),
            Forms\Components\TextInput::make('type'),
            Forms\Components\TextInput::make('max_hours')
                ->numeric()
                ->required(),
        ];
    }
}
