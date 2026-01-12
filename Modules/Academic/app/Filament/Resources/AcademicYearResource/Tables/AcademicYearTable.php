<?php

namespace Modules\Academic\Filament\Resources\AcademicYearResource\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class AcademicYearTable
{
    public static function columns(): array
    {
        return [
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
