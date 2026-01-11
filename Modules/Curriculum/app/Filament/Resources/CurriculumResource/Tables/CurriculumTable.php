<?php

namespace Modules\Curriculum\Filament\Resources\CurriculumResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class CurriculumTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('departments.name')
                ->label(__('department::app.Departments'))
                ->formatStateUsing(fn ($state) => $state instanceof \Illuminate\Support\Collection ? $state->map(fn($name) => self::getTranslatedName($name))->join(', ') : self::getTranslatedName($state))
                ->badge()
                ->separator(',')
                ->searchable(),

            TextColumn::make('name')
                ->label(__('app.Name'))
                ->formatStateUsing(fn ($state) => self::getTranslatedName($state))
                ->searchable(),

            TextColumn::make('code')
                ->label(__('app.Code'))
                ->searchable(),

            TextColumn::make('subjects_count')
                ->label(__('subject::app.Subjects'))
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
            SelectFilter::make('departments')
                ->label(__('department::app.Departments'))
                ->relationship('departments', 'name')
                ->preload()
                ->searchable(),

            SelectFilter::make('status')
                ->label(__('app.Status'))
                ->options([
                    'active' => __('app.Active'),
                    'archived' => __('app.Archived'),
                ]),
        ];
    }

    protected static function getTranslatedName($name): string
    {
        if (is_array($name)) {
            return $name[app()->getLocale()] ?? $name['en'] ?? '';
        }
        return $name ?? '';
    }
}
