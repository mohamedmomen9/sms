<?php

namespace App\Filament\Resources\UserResource\Tables;

use App\Models\Faculty;
use App\Models\University;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Facades\Auth;

class UserTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('username')
                ->label('Username')
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('email')
                ->label('Email')
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('display_name')
                ->label('Display Name')
                ->searchable()
                ->sortable()
                ->toggleable(),

            IconColumn::make('is_admin')
                ->label('Admin')
                ->boolean()
                ->trueIcon('heroicon-o-shield-check')
                ->falseIcon('heroicon-o-user')
                ->color(fn (bool $state): string => $state ? 'success' : 'gray')
                ->alignCenter(),

            TextColumn::make('scope_type')
                ->label('Scope')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Admin (Global Access)' => 'success',
                    'University' => 'primary',
                    'Faculty' => 'info',
                    'Subject' => 'warning',
                    default => 'gray',
                }),

            TextColumn::make('university.name')
                ->label('University')
                ->sortable()
                ->toggleable()
                ->placeholder('-'),

            TextColumn::make('faculty.name')
                ->label('Faculty')
                ->sortable()
                ->toggleable()
                ->placeholder('-'),

            TextColumn::make('subject.name_en')
                ->label('Subject')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->placeholder('-'),

            TextColumn::make('role')
                ->label('Role')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'admin' => 'primary',
                    'faculty_member' => 'success',
                    'student' => 'info',
                    'staff' => 'warning',
                    default => 'secondary',
                })
                ->sortable()
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

        // Admin filter
        $filters[] = TernaryFilter::make('is_admin')
            ->label('Administrator')
            ->trueLabel('Admins Only')
            ->falseLabel('Non-Admins Only');

        // University filter - only for admins
        if ($user && $user->isAdmin()) {
            $filters[] = SelectFilter::make('university_id')
                ->label('University')
                ->options(University::pluck('name', 'id'))
                ->searchable()
                ->preload();
        }

        // Faculty filter
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

        // Role filter
        $filters[] = SelectFilter::make('role')
            ->label('Role')
            ->options([
                'admin' => 'Admin',
                'faculty_member' => 'Faculty Member',
                'student' => 'Student',
                'staff' => 'Staff',
            ]);

        return $filters;
    }
}
