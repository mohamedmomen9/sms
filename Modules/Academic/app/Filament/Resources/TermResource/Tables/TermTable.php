<?php

namespace Modules\Academic\Filament\Resources\TermResource\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class TermTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('academicYear.name')
                ->label('Academic Year')
                ->sortable(),
            
            TextColumn::make('name')
                ->sortable()
                ->searchable(),

            TextColumn::make('start_date')
                ->date()
                ->sortable(),

            TextColumn::make('end_date')
                ->date()
                ->sortable(),

            IconColumn::make('is_active')
                ->boolean()
                ->sortable(),
        ];
    }
}
