<?php

namespace Modules\Teachers\Filament\Resources\TeacherResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TeacherTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('name')
                ->label(__('Name'))
                ->searchable()
                ->sortable(),
            TextColumn::make('email')
                ->label(__('Email'))
                ->searchable(),
            TextColumn::make('phone')
                ->label(__('Phone'))
                ->searchable(),
            TextColumn::make('qualification')
                ->label(__('Qualification'))
                ->searchable()
                ->toggleable(),
            TextColumn::make('campus.name')
                ->label(__('Campus'))
                ->sortable()
                ->toggleable(),
            TextColumn::make('faculties.name')
                ->label(__('Faculties'))
                ->badge()
                ->separator(', ')
                ->toggleable(),
            TextColumn::make('subjects_count')
                ->label(__('Subjects'))
                ->counts('subjects')
                ->sortable(),
            TextColumn::make('created_at')
                ->label(__('Created'))
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            SelectFilter::make('campus_id')
                ->label(__('Campus'))
                ->relationship('campus', 'name'),
            SelectFilter::make('faculties')
                ->label(__('Faculty'))
                ->relationship('faculties', 'name')
                ->multiple()
                ->preload(),
        ];
    }
}
