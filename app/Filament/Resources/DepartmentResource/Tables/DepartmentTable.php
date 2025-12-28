<?php

namespace App\Filament\Resources\DepartmentResource\Tables;

use App\Models\Faculty;
use App\Models\University;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class DepartmentTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('faculty.university.name')
                ->label('University')
                ->sortable()
                ->searchable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('faculty.name')
                ->label('Faculty')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('code')
                ->label('Code')
                ->searchable()
                ->sortable()
                ->copyable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable()
                ->limit(40),

            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'success' => 'active',
                    'danger' => 'inactive',
                    'warning' => 'pending',
                ])
                ->sortable(),

            Tables\Columns\TextColumn::make('subjects_count')
                ->label('Subjects')
                ->counts('subjects')
                ->sortable(),

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

        // University filter - only for admins
        if ($user && $user->isAdmin()) {
            $filters[] = SelectFilter::make('university')
                ->label('University')
                ->relationship('faculty.university', 'name')
                ->searchable()
                ->preload();
        }

        // Faculty filter - for admins and university-scoped users
        if ($user && ($user->isAdmin() || $user->isScopedToUniversity())) {
            $filters[] = SelectFilter::make('faculty_id')
                ->label('Faculty')
                ->options(function () use ($user) {
                    if ($user->isAdmin()) {
                        return Faculty::pluck('name', 'id');
                    }
                    if ($user->isScopedToUniversity()) {
                        return Faculty::where('university_id', $user->university_id)->pluck('name', 'id');
                    }
                    return [];
                })
                ->searchable()
                ->preload();
        }

        // Status filter for all users
        $filters[] = SelectFilter::make('status')
            ->label('Status')
            ->options([
                'active' => 'Active',
                'inactive' => 'Inactive',
                'pending' => 'Pending',
            ]);

        return $filters;
    }
}
