<?php

namespace App\Filament\Resources\SubjectResource\Tables;

use Filament\Tables;

class SubjectTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('department.name')->label('Department')->sortable(),
            Tables\Columns\TextColumn::make('curriculum')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('code')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('name_en')->label('Name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('name_ar')->label('Name (Ar)')->toggleable(),
            Tables\Columns\TextColumn::make('max_hours')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ];
    }
}
