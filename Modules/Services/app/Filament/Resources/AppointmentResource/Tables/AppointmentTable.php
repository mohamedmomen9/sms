<?php

namespace Modules\Services\Filament\Resources\AppointmentResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class AppointmentTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('id')
                ->sortable(),

            TextColumn::make('student.name')
                ->label('Student')
                ->searchable()
                ->sortable(),

            TextColumn::make('student.student_id')
                ->label('Student ID')
                ->searchable(),

            TextColumn::make('department.name')
                ->sortable(),

            TextColumn::make('purpose.name'),

            TextColumn::make('appointment_date')
                ->date()
                ->sortable(),

            TextColumn::make('slot.label')
                ->label('Time'),

            TextColumn::make('status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'completed' => 'success',
                    'booked' => 'warning',
                    'cancelled', 'no_show' => 'danger',
                    default => 'gray',
                }),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            SelectFilter::make('status')
                ->options([
                    'booked' => 'Booked',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    'no_show' => 'No Show',
                ]),

            SelectFilter::make('department')
                ->relationship('department', 'name'),

            Filter::make('today')
                ->query(fn($query) => $query->whereDate('appointment_date', today()))
                ->label('Today Only'),

            Filter::make('upcoming')
                ->query(fn($query) => $query->whereDate('appointment_date', '>=', today()))
                ->label('Upcoming'),
        ];
    }
}
