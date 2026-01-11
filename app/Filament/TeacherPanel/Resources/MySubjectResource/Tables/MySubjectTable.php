<?php

namespace App\Filament\TeacherPanel\Resources\MySubjectResource\Tables;

use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class MySubjectTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('code')
                ->label(__('Code'))
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->label(__('Name'))
                ->searchable()
                ->sortable(),
            TextColumn::make('faculty.name')
                ->label(__('Faculty'))
                ->sortable(),
            TextColumn::make('department.name')
                ->label(__('Department'))
                ->sortable()
                ->toggleable(),
        ];
    }

    public static function filters(): array
    {
        return [
            SelectFilter::make('faculty_id')
                ->label(__('Faculty'))
                ->relationship('faculty', 'name')
                ->preload(),
        ];
    }

    public static function actions(): array
    {
        return [
            ViewAction::make(),
        ];
    }
}
