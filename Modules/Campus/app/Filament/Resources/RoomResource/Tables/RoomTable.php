<?php

namespace Modules\Campus\Filament\Resources\RoomResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class RoomTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('building.name')
                ->sortable()
                ->searchable(),
            TextColumn::make('floor_number')
                ->sortable(),
            TextColumn::make('number')
                ->label(__('Room #'))
                ->searchable(),
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('type')
                ->sortable()
                ->badge(),
            TextColumn::make('capacity')
                ->numeric(),
            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'inactive' => 'gray',
                    'maintenance' => 'warning',
                    default => 'gray',
                }),
        ];
    }

    public static function filters(): array
    {
        return [
            SelectFilter::make('building')
                ->relationship('building', 'name'),
            SelectFilter::make('type')
                ->options([
                    'classroom' => __('Classroom'),
                    'lab' => __('Lab'),
                    'auditorium' => __('Auditorium'),
                    'office' => __('Office'),
                ]),
            SelectFilter::make('status')
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive'),
                    'maintenance' => __('Maintenance'),
                ]),
        ];
    }
}
