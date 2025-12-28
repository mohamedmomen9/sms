<?php

namespace App\Filament\Resources\UniversityResource\Tables;

use Filament\Tables;

class UniversityTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\ImageColumn::make('logo')
                ->label('Logo')
                ->circular()
                ->size(40)
                ->toggleable(),

            Tables\Columns\TextColumn::make('code')
                ->label('Code')
                ->searchable()
                ->sortable()
                ->copyable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable()
                ->limit(50),

            Tables\Columns\TextColumn::make('faculties_count')
                ->label('Faculties')
                ->counts('faculties')
                ->sortable()
                ->alignCenter(),

            Tables\Columns\TextColumn::make('departments_count')
                ->label('Departments')
                ->counts('departments')
                ->sortable()
                ->alignCenter(),

            Tables\Columns\TextColumn::make('users_count')
                ->label('Users')
                ->counts('users')
                ->sortable()
                ->alignCenter()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('created_at')
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
