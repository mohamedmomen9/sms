<?php

namespace Modules\Students\Filament\Resources\StudentResource\Tables;

use Filament\Tables\Columns\TextColumn;

class StudentTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('email')
                ->searchable(),
            TextColumn::make('student_id')
                ->searchable()
                ->label('ID'),
            TextColumn::make('campus.name')
                ->sortable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            \Filament\Tables\Filters\SelectFilter::make('campus')
                ->relationship('campus', 'name'),
            \Filament\Tables\Filters\SelectFilter::make('department')
                ->relationship('department', 'name'),
        ];
    }
}
