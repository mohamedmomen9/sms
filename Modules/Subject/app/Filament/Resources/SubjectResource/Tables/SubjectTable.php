<?php

namespace Modules\Subject\Filament\Resources\SubjectResource\Tables;

use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class SubjectTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('faculty.name')
                ->label(__('subject::app.Faculty'))
                ->sortable()
                ->searchable()
                ->getStateUsing(function ($record) {
                    if ($record->faculty_id) {
                        return $record->faculty?->name;
                    }
                    return $record->department?->faculty?->name;
                }),

            TextColumn::make('department.name')
                ->label(__('subject::app.Department'))
                ->sortable()
                ->searchable()
                ->placeholder(__('subject::app.Direct to Faculty'))
                ->toggleable(),

            TextColumn::make('code')
                ->label(__('app.Code'))
                ->sortable()
                ->searchable()
                ->copyable(),

            TextColumn::make('name')
                ->label(__('app.Name'))
                ->sortable()
                ->searchable()
                ->limit(35),

            TextColumn::make('created_at')
                ->label(__('app.Created'))
                ->dateTime('M d, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        $filters = [];

        $filters[] = SelectFilter::make('faculty_id')
            ->label(__('subject::app.Faculty'))
            ->options(Faculty::all()->pluck('name', 'id'))
            ->searchable();

        $filters[] = SelectFilter::make('department_id')
            ->label(__('subject::app.Department'))
            ->options(Department::all()->pluck('name', 'id'))
            ->searchable();

        return $filters;
    }
}
