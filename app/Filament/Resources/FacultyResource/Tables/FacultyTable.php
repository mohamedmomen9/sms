<?php

namespace App\Filament\Resources\FacultyResource\Tables;

use Filament\Tables;

class FacultyTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('university.name')->label('University')->sortable(),
            Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ];
    }
}
