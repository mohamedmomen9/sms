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
                ->label(__('academic::app.Name'))
                ->required()
                ->maxLength(255)
                ->placeholder(__('academic::app.Example Year')),
            DatePicker::make('start_date')
                ->label(__('academic::app.Start Date'))
                ->required(),
            DatePicker::make('end_date')
                ->label(__('academic::app.End Date'))
                ->required(),
            Toggle::make('is_active')
                ->label(__('academic::app.Is Active'))
                ->required()
                ->inline(false)
                ->default(false),
        ];
    }
}
