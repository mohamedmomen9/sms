<?php

namespace Modules\Campus\Filament\Resources\FacilityResource\Tables;

use Filament\Tables\Columns\TextColumn;

class FacilityTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable()
                ->sortable(),
            TextColumn::make('rooms_count')
                ->counts('rooms')
                ->label(__('Rooms')),
        ];
    }
}
