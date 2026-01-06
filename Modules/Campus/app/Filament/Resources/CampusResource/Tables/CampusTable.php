<?php

namespace Modules\Campus\Filament\Resources\CampusResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class CampusTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('code')
                ->label(__('app.Code'))
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('name')
                ->label(__('app.Name'))
                ->searchable()
                ->sortable()
                ->limit(40),

            TextColumn::make('location')
                ->label(__('app.Location'))
                ->searchable()
                ->toggleable(),

            TextColumn::make('faculties_count')
                ->label(__('app.Faculties'))
                ->counts('faculties')
                ->sortable()
                ->alignCenter(),

            TextColumn::make('status')
                ->label(__('app.Status'))
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'inactive' => 'danger',
                    default => 'secondary',
                }),

            TextColumn::make('phone')
                ->label(__('app.Phone'))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('email')
                ->label(__('app.Email'))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label(__('app.Created'))
                ->dateTime('M d, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $filters = [];

        $filters[] = SelectFilter::make('status')
            ->label(__('app.Status'))
            ->options([
                'active' => __('app.Active'),
                'inactive' => __('app.Inactive'),
            ]);

        return $filters;
    }
}
