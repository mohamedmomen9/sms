<?php

namespace App\Filament\Resources\DepartmentResource\Tables;

use Filament\Tables;

class DepartmentTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('faculty.name')->label('Faculty')->sortable(),
            Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('status')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ];
    }
}
