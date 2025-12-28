<?php

namespace App\Filament\Resources\FacultyResource\Tables;

use App\Models\Campus;
use App\Models\University;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class FacultyTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('university.name')
                ->label('University')
                ->sortable()
                ->searchable(),

            TextColumn::make('campus.name')
                ->label('Campus')
                ->sortable()
                ->searchable()
                ->placeholder('No Campus')
                ->toggleable(),

            TextColumn::make('code')
                ->label('Code')
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable()
                ->limit(40),

            TextColumn::make('departments_count')
                ->label('Departments')
                ->counts('departments')
                ->sortable()
                ->alignCenter(),

            TextColumn::make('subjects_count')
                ->label('Subjects')
                ->counts('subjects')
                ->sortable()
                ->alignCenter(),

            TextColumn::make('users_count')
                ->label('Users')
                ->counts('users')
                ->sortable()
                ->alignCenter()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
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
            $filters[] = SelectFilter::make('university_id')
                ->label('University')
                ->options(University::pluck('name', 'id'))
                ->searchable()
                ->preload();
        }

        // Campus filter
        if ($user && ($user->isAdmin() || $user->isScopedToUniversity())) {
            $filters[] = SelectFilter::make('campus_id')
                ->label('Campus')
                ->options(function () use ($user) {
                    if ($user->isAdmin()) {
                        return Campus::with('university')->get()->mapWithKeys(function ($campus) {
                            return [$campus->id => "{$campus->university->name} - {$campus->name}"];
                        });
                    }
                    if ($user->isScopedToUniversity()) {
                        return Campus::where('university_id', $user->university_id)->pluck('name', 'id');
                    }
                    return [];
                })
                ->searchable()
                ->preload();
        }

        return $filters;
    }
}
