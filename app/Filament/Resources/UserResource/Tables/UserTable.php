<?php

namespace App\Filament\Resources\UserResource\Tables;

use App\Models\Faculty;
use App\Models\University;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Facades\Auth;

class UserTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('username')
                ->label('Username')
                ->searchable()
                ->sortable()
                ->copyable(),

            Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->searchable()
                ->sortable()
                ->copyable(),

            Tables\Columns\TextColumn::make('display_name')
                ->label('Display Name')
                ->searchable()
                ->sortable()
                ->toggleable(),

            Tables\Columns\IconColumn::make('is_admin')
                ->label('Admin')
                ->boolean()
                ->trueIcon('heroicon-o-shield-check')
                ->falseIcon('heroicon-o-user')
                ->trueColor('success')
                ->falseColor('gray')
                ->alignCenter(),

            Tables\Columns\TextColumn::make('scope_type')
                ->label('Scope')
                ->badge()
                ->colors([
                    'success' => 'Admin (Global Access)',
                    'primary' => 'University',
                    'info' => 'Faculty',
                    'warning' => 'Subject',
                    'gray' => 'None',
                ]),

            Tables\Columns\TextColumn::make('university.name')
                ->label('University')
                ->sortable()
                ->toggleable()
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('faculty.name')
                ->label('Faculty')
                ->sortable()
                ->toggleable()
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('subject.name_en')
                ->label('Subject')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('role')
                ->label('Role')
                ->badge()
                ->colors([
                    'primary' => 'admin',
                    'success' => 'faculty_member',
                    'info' => 'student',
                    'warning' => 'staff',
                ])
                ->sortable()
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
