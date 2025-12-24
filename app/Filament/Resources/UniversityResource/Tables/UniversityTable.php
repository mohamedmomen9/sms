<?php

namespace App\Filament\Resources\UniversityResource\Tables;

use Filament\Tables;

class UniversityTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\ImageColumn::make('logo'),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ];
    }
}
