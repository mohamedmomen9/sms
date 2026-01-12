<?php

namespace Modules\Subject\Filament\Resources\CourseOfferingResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class CourseOfferingTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('term.name')
                ->label(__('Term'))
                ->sortable()
                ->searchable(),
            TextColumn::make('subject.name')
                ->label(__('Subject'))
                ->sortable()
                ->searchable(),
            TextColumn::make('section_number')
                ->label(__('Section'))
                ->sortable(),
            TextColumn::make('teacher.name')
                ->label(__('Instructor'))
                ->sortable()
                ->searchable(),
            TextColumn::make('schedules_count')
                ->counts('schedules')
                ->label(__('Schedule'))
                ->formatStateUsing(fn ($state) => $state . ' ' . trans_choice('session|sessions', $state))
                ->badge()
                ->color('info'),
            TextColumn::make('capacity')
                ->numeric()
                ->sortable(),
            TextColumn::make('enrollments_count')
                ->counts('enrollments')
                ->label(__('Enrolled')),
        ];
    }

    public static function filters(): array
    {
        return [
            SelectFilter::make('term')
                ->relationship('term', 'name'),
            SelectFilter::make('teacher')
                ->relationship('teacher', 'name'),
        ];
    }
}
