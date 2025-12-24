<?php

namespace App\Filament\Resources\PermissionResource\Tables;

use Filament\Tables;

class PermissionTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('guard_name'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ];
    }
}
