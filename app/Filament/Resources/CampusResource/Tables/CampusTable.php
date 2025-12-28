<?php

namespace App\Filament\Resources\CampusResource\Tables;

use App\Models\University;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class CampusTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('university.name')
                ->label('University')
                ->sortable()
                ->searchable(),

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

            TextColumn::make('location')
                ->label('Location')
                ->searchable()
                ->toggleable(),

            TextColumn::make('faculties_count')
                ->label('Faculties')
                ->counts('faculties')
                ->sortable()
                ->alignCenter(),

            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'inactive' => 'danger',
                    default => 'secondary',
                }),

            TextColumn::make('phone')
                ->label('Phone')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('email')
                ->label('Email')
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

        // Status filter
        $filters[] = SelectFilter::make('status')
            ->label('Status')
            ->options([
                'active' => 'Active',
                'inactive' => 'Inactive',
            ]);

        return $filters;
    }
}
