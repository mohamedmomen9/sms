<?php

namespace Modules\Academic\Filament\Resources\TermResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TermForm
{
    public static function schema(): array
    {
        return [
            Select::make('academic_year_id')
                ->relationship('academicYear', 'name')
                ->required(),
            
            Select::make('name')
                ->options([
                    'FALL' => 'FALL',
                    'SPRING' => 'SPRING',
                    'SUMMER' => 'SUMMER',
                    'WINTER' => 'WINTER',
                ])
                ->required()
                ->native(false),

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
