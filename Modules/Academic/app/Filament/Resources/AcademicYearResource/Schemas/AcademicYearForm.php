<?php

namespace Modules\Academic\Filament\Resources\AcademicYearResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class AcademicYearForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->placeholder('e.g. 2025-2026'),
            DatePicker::make('start_date')
                ->required(),
            DatePicker::make('end_date')
                ->required(),
            Toggle::make('is_active')
                ->required()
                ->inline(false)
                ->default(false),
        ];
    }
}
