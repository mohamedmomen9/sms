<?php

namespace App\Filament\Resources\FacultyResource\Tables;

use App\Models\Campus;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class FacultyTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('campus.name')
                ->label(__('app.Campus'))
                ->sortable()
                ->searchable()
                ->placeholder(__('app.No Campus'))
                ->toggleable(),

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



            TextColumn::make('users_count')
                ->label(__('app.Users'))
                ->counts('users')
                ->sortable()
                ->alignCenter()
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

        $filters[] = SelectFilter::make('campus_id')
            ->label(__('app.Campus'))
            ->relationship('campus', 'name')
            ->searchable()
            ->preload();

        return $filters;
    }
}
