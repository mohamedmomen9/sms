<?php

namespace Modules\Academic\Filament\Resources\SubjectResource\Tables;

use Modules\Academic\Models\Department;
use Modules\Academic\Models\Faculty;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class SubjectTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('faculty.name')
                ->label(__('app.Faculty'))
                ->sortable()
                ->searchable()
                ->getStateUsing(function ($record) {
                    if ($record->faculty_id) {
                        return $record->faculty?->name;
                    }
                    return $record->department?->faculty?->name;
                }),

            TextColumn::make('department.name')
                ->label(__('app.Department'))
                ->sortable()
                ->searchable()
                ->placeholder(__('app.Direct to Faculty'))
                ->toggleable(),

            TextColumn::make('curriculum')
                ->label(__('app.Curriculum'))
                ->sortable()
                ->searchable()
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

            TextColumn::make('category')
                ->label(__('app.Category'))
                ->badge()
                ->color(fn ($state): string => match ($state) {
                    'core' => 'primary',
                    'elective' => 'success',
                    'general' => 'info',
                    'specialization' => 'warning',
                    default => 'secondary',
                })
                ->toggleable(),

            TextColumn::make('type')
                ->label(__('app.Type'))
                ->badge()
                ->color(fn ($state): string => match ($state) {
                    'theoretical' => 'info',
                    'practical' => 'success',
                    'mixed' => 'warning',
                    default => 'secondary',
                })
                ->toggleable(),

            TextColumn::make('max_hours')
                ->label(__('app.Hours'))
                ->sortable()
                ->alignCenter(),

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
            ->options(Faculty::all()->pluck('name', 'id'))
            ->searchable();

        $filters[] = SelectFilter::make('department_id')
            ->label(__('app.Department'))
            ->options(Department::all()->pluck('name', 'id'))
            ->searchable();

        $filters[] = SelectFilter::make('category')
            ->label(__('app.Category'))
            ->options([
                'core' => __('app.Core'),
                'elective' => __('app.Elective'),
                'general' => __('app.General'),
                'specialization' => __('app.Specialization'),
            ]);

        $filters[] = SelectFilter::make('type')
            ->label(__('app.Type'))
            ->options([
                'theoretical' => __('app.Theoretical'),
                'practical' => __('app.Practical'),
                'mixed' => __('app.Mixed'),
            ]);

        return $filters;
    }
}
