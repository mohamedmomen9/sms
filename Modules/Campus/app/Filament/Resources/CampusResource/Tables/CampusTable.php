<?php

namespace Modules\Campus\Filament\Resources\CampusResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class CampusTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('code')
                ->label(__('campus::app.Code'))
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('name')
                ->label(__('campus::app.Name'))
                ->searchable()
                ->sortable()
                ->limit(40),

            TextColumn::make('location')
                ->label(__('campus::app.Location'))
                ->searchable()
                ->toggleable(),

            TextColumn::make('faculties_count')
                ->label(__('campus::app.Faculties'))
                ->counts('faculties')
                ->sortable()
                ->alignCenter(),

            TextColumn::make('status')
                ->label(__('campus::app.Status'))
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'inactive' => 'danger',
                    default => 'secondary',
                }),

            TextColumn::make('phone')
                ->label(__('campus::app.Phone'))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('email')
                ->label(__('campus::app.Email'))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label(__('campus::app.Created'))
                ->dateTime('M d, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        $filters = [];

        $filters[] = SelectFilter::make('status')
            ->label(__('campus::app.Status'))
            ->options([
                'active' => __('campus::app.Active'),
                'inactive' => __('campus::app.Inactive'),
            ]);

        return $filters;
    }
}
