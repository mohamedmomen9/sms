<?php

namespace Modules\Subject\Filament\Resources\CourseOfferingResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Teachers\Models\Teacher;

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
            TextColumn::make('instructor_names')
                ->label(__('Instructors'))
                ->wrap()
                ->searchable(query: function ($query, string $search) {
                    return $query->whereHas('teachers', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                }),
            TextColumn::make('teachers_count')
                ->counts('teachers')
                ->label(__('# Instructors'))
                ->badge()
                ->color('success'),
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
                ->label(__('Instructor'))
                ->options(Teacher::pluck('name', 'id'))
                ->query(function ($query, array $data) {
                    if ($data['value']) {
                        $query->whereHas('teachers', fn ($q) => $q->where('teachers.id', $data['value']));
                    }
                }),
        ];
    }
}

