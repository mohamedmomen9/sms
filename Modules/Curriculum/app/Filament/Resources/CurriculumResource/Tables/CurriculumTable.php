<?php

namespace Modules\Curriculum\Filament\Resources\CurriculumResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class CurriculumTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('department.name')
                ->label(__('app.Department'))
                ->sortable()
                ->searchable(),

            TextColumn::make('name')
                ->label(__('app.Name'))
                ->searchable(),

            TextColumn::make('code')
                ->label(__('app.Code'))
                ->searchable(),

            TextColumn::make('subjects_count')
                ->label(__('app.Subjects'))
                ->counts('subjects')
                ->sortable(),

            TextColumn::make('status')
                ->label(__('app.Status'))
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'archived' => 'gray',
                    default => 'gray',
                }),

            TextColumn::make('created_at')
                ->label(__('app.Created'))
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            SelectFilter::make('department_id')
                ->label(__('app.Department'))
                ->relationship('department', 'name')
                ->searchable()
                ->preload(),

            SelectFilter::make('status')
                ->label(__('app.Status'))
                ->options([
                    'active' => __('app.Active'),
                    'archived' => __('app.Archived'),
                ]),
        ];
    }
}
