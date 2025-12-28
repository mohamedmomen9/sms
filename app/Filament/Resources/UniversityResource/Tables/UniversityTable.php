<?php

namespace App\Filament\Resources\UniversityResource\Tables;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class UniversityTable
{
    public static function columns(): array
    {
        return [
            ImageColumn::make('logo')
                ->label('Logo')
                ->circular()
                ->size(40)
                ->toggleable(),

            TextColumn::make('code')
                ->label('Code')
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable()
                ->limit(50),

            TextColumn::make('campuses_count')
                ->label('Campuses')
                ->counts('campuses')
                ->sortable()
                ->alignCenter(),

            TextColumn::make('faculties_count')
                ->label('Faculties')
                ->counts('faculties')
                ->sortable()
                ->alignCenter(),

            TextColumn::make('departments_count')
                ->label('Departments')
                ->counts('departments')
                ->sortable()
                ->alignCenter(),

            TextColumn::make('users_count')
                ->label('Users')
                ->counts('users')
                ->sortable()
                ->alignCenter()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('Created')
                ->dateTime('M d, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [];
    }
}
