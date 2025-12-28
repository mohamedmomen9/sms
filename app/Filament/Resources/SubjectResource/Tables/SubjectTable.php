<?php

namespace App\Filament\Resources\SubjectResource\Tables;

use App\Models\Department;
use App\Models\Faculty;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class SubjectTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('effective_faculty.university.name')
                ->label('University')
                ->sortable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('faculty.name')
                ->label('Faculty')
                ->sortable()
                ->searchable()
                ->getStateUsing(function ($record) {
                    // Get faculty either directly or through department
                    if ($record->faculty_id) {
                        return $record->faculty?->name;
                    }
                    return $record->department?->faculty?->name;
                }),

            Tables\Columns\TextColumn::make('department.name')
                ->label('Department')
                ->sortable()
                ->searchable()
                ->placeholder('Direct to Faculty')
                ->toggleable(),

            Tables\Columns\TextColumn::make('curriculum')
                ->label('Curriculum')
                ->sortable()
                ->searchable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('code')
                ->label('Code')
                ->sortable()
                ->searchable()
                ->copyable(),

            Tables\Columns\TextColumn::make('name_en')
                ->label('Name')
                ->sortable()
                ->searchable()
                ->limit(35),

            Tables\Columns\TextColumn::make('name_ar')
                ->label('Name (Arabic)')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\BadgeColumn::make('category')
                ->label('Category')
                ->colors([
                    'primary' => 'core',
                    'success' => 'elective',
                    'info' => 'general',
                    'warning' => 'specialization',
                ])
                ->toggleable(),

            Tables\Columns\BadgeColumn::make('type')
                ->label('Type')
                ->colors([
                    'info' => 'theoretical',
                    'success' => 'practical',
                    'warning' => 'mixed',
                ])
                ->toggleable(),

            Tables\Columns\TextColumn::make('max_hours')
                ->label('Hours')
                ->sortable()
                ->alignCenter(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Created')
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

        // Faculty filter - for admins and university-scoped users
        if ($user && ($user->isAdmin() || $user->isScopedToUniversity())) {
            $filters[] = SelectFilter::make('faculty_id')
                ->label('Faculty')
                ->options(function () use ($user) {
                    if ($user->isAdmin()) {
                        return Faculty::with('university')->get()->mapWithKeys(function ($faculty) {
                            return [$faculty->id => "{$faculty->university->name} - {$faculty->name}"];
                        });
                    }
                    if ($user->isScopedToUniversity()) {
                        return Faculty::where('university_id', $user->university_id)->pluck('name', 'id');
                    }
                    return [];
                })
                ->searchable()
                ->preload();
        }

        // Department filter
        if ($user && ($user->isAdmin() || $user->isScopedToUniversity() || $user->isScopedToFaculty())) {
            $filters[] = SelectFilter::make('department_id')
                ->label('Department')
                ->options(function () use ($user) {
                    $query = Department::query();
                    
                    if (!$user->isAdmin()) {
                        $facultyIds = $user->getAccessibleFacultyIds();
                        $query->whereIn('faculty_id', $facultyIds);
                    }
                    
                    return $query->pluck('name', 'id');
                })
                ->searchable()
                ->preload();
        }

        // Category filter
        $filters[] = SelectFilter::make('category')
            ->label('Category')
            ->options([
                'core' => 'Core',
                'elective' => 'Elective',
                'general' => 'General',
                'specialization' => 'Specialization',
            ]);

        // Type filter
        $filters[] = SelectFilter::make('type')
            ->label('Type')
            ->options([
                'theoretical' => 'Theoretical',
                'practical' => 'Practical',
                'mixed' => 'Mixed',
            ]);

        return $filters;
    }
}
