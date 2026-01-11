<?php

namespace Modules\Faculty\Filament\Resources\FacultyResource\Schemas;

use App\Filament\Forms\Components\TranslatableInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FacultyForm
{
    public static function schema(): array
    {
        return [
            Section::make(__('app.Campus Assignment'))
                ->description(__('app.Optionally assign this faculty to a specific campus'))
                ->schema([
                    Select::make('campus_id')
                        ->label(__('app.Campus (Optional)'))
                        ->relationship('campus', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->helperText(__('app.Leave empty if faculty is not assigned to a specific campus')),
                ]),

            Section::make(__('app.Faculty Details'))
                ->schema([
                    TextInput::make('code')
                        ->label(__('app.Faculty Code'))
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TranslatableInput::make('name', TextInput::class, function ($field, $locale) {
                        return $field->required()->maxLength(255);
                    }),
                ])
                ->columns(1),
        ];
    }
}
