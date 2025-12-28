<?php

namespace App\Filament\Resources\PermissionResource\Tables;

use Filament\Tables\Columns\TextColumn;

class PermissionTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('guard_name'),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ];
    }
}
