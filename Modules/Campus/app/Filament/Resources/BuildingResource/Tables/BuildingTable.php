<?php

namespace Modules\Campus\Filament\Resources\BuildingResource\Tables;

use Filament\Tables\Columns\TextColumn;

class BuildingTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('campus.name')
                ->sortable()
                ->searchable(),
            TextColumn::make('name')
                ->searchable()
                ->sortable(),
            TextColumn::make('code')
                ->searchable(),
            TextColumn::make('rooms_count')
                ->counts('rooms')
                ->label(__('Rooms')),
        ];
    }
}
