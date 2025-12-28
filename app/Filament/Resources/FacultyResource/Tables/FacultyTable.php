<?php

namespace App\Filament\Resources\FacultyResource\Tables;

use App\Models\University;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class FacultyTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('university.name')
                ->label('University')
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

            Tables\Columns\TextColumn::make('departments_count')
                ->label('Departments')
                ->counts('departments')
                ->sortable()
                ->alignCenter(),

            Tables\Columns\TextColumn::make('subjects_count')
                ->label('Subjects')
                ->counts('subjects')
                ->sortable()
                ->alignCenter(),

            Tables\Columns\TextColumn::make('users_count')
                ->label('Users')
                ->counts('users')
                ->sortable()
                ->alignCenter()
                ->toggleable(isToggledHiddenByDefault: true),

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
            $filters[] = SelectFilter::make('university_id')
                ->label('University')
                ->options(University::pluck('name', 'id'))
                ->searchable()
                ->preload();
        }

        return $filters;
    }
}
