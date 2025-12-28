<?php

namespace App\Filament\Resources\CurriculumResource\Schemas;

use App\Filament\Forms\Components\TranslatableInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CurriculumForm
{
    public static function schema(): array
    {
        return [
            Section::make(__('app.Curriculum Details'))
                ->schema([
                    Select::make('department_id')
                        ->label(__('app.Department'))
                        ->relationship('department', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    TextInput::make('code')
                        ->label(__('app.Code'))
                        ->maxLength(255),

                    Select::make('status')
                        ->label(__('app.Status'))
                        ->options([
                            'active' => __('app.Active'),
                            'archived' => __('app.Archived'),
                        ])
                        ->required()
                        ->default('active'),
                ])
                ->columns(2),

            Section::make(__('app.Name'))
                ->schema([
                    TranslatableInput::make('name', TextInput::class, function ($field, $locale) {
                        return $field->required()->maxLength(255);
                    }),
                ]),
        ];
    }
}
