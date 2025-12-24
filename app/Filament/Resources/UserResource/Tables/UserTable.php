<?php

namespace App\Filament\Resources\UserResource\Tables;

use Filament\Tables;

class UserTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('username')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('role')->sortable(),
            Tables\Columns\TextColumn::make('faculty.name')->label('Faculty')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ];
    }
}
