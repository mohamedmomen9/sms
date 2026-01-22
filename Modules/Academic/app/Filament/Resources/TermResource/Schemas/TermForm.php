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
                ->label(__('academic::app.Academic Year'))
                ->relationship('academicYear', 'name')
                ->required(),

            Select::make('name')
                ->label(__('academic::app.Name'))
                ->options([
                    'FALL' => __('academic::app.Fall'),
                    'SPRING' => __('academic::app.Spring'),
                    'SUMMER' => __('academic::app.Summer'),
                    'WINTER' => __('academic::app.Winter'),
                ])
                ->required()
                ->native(false),

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
