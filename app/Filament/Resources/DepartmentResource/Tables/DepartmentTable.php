<?php

namespace App\Filament\Resources\DepartmentResource\Tables;

use App\Models\Faculty;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class DepartmentTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('faculty.name')
                ->label(__('app.Faculty'))
                ->sortable()
                ->searchable(),

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

            TextColumn::make('status')
                ->label(__('app.Status'))
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'inactive' => 'danger',
                    'pending' => 'warning',
                    default => 'secondary',
                })
                ->sortable(),

            TextColumn::make('subjects_count')
                ->label(__('app.Subjects'))
                ->counts('subjects')
                ->sortable(),

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

        $filters[] = SelectFilter::make('faculty_id')
            ->label(__('app.Faculty'))
            ->relationship('faculty', 'name')
            ->searchable()
            ->preload();

        $filters[] = SelectFilter::make('status')
            ->label(__('app.Status'))
            ->options([
                'active' => __('app.Active'),
                'inactive' => __('app.Inactive'),
                'pending' => __('app.Pending'),
            ]);

        return $filters;
    }
}
