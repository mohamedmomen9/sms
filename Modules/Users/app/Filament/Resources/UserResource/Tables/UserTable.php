<?php

namespace Modules\Users\Filament\Resources\UserResource\Tables;

use Modules\Faculty\Models\Faculty;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class UserTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('username')
                ->label(__('users::app.Username'))
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('email')
                ->label(__('users::app.Email'))
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('display_name')
                ->label(__('users::app.Display Name'))
                ->searchable()
                ->sortable()
                ->toggleable(),

            IconColumn::make('is_admin')
                ->label(__('users::app.Admin'))
                ->boolean()
                ->trueIcon('heroicon-o-shield-check')
                ->falseIcon('heroicon-o-user')
                ->color(fn (bool $state): string => $state ? 'success' : 'gray')
                ->alignCenter(),

            TextColumn::make('scope_type')
                ->label(__('users::app.Scope'))
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Admin (Global Access)' => 'success',
                    'University' => 'primary',
                    'Faculty' => 'info',
                    'Subject' => 'warning',
                    default => 'gray',
                }),

            TextColumn::make('faculty.name')
                ->label(__('faculty::app.Faculty'))
                ->sortable()
                ->toggleable()
                ->placeholder('-'),

            TextColumn::make('subject.name')
                ->label(__('subject::app.Subject'))
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->placeholder('-'),

            TextColumn::make('role')
                ->label(__('users::app.Role'))
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
                ->label(__('app.Created'))
                ->dateTime('M d, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        $filters = [];

        $filters[] = TernaryFilter::make('is_admin')
            ->label(__('users::app.Administrator'))
            ->trueLabel(__('users::app.Admins Only'))
            ->falseLabel(__('users::app.Non-Admins Only'));

        $filters[] = SelectFilter::make('faculty_id')
            ->label(__('faculty::app.Faculty'))
            ->options(Faculty::all()->pluck('name', 'id'))
            ->searchable();

        $filters[] = SelectFilter::make('role')
            ->label(__('users::app.Role'))
            ->options([
                'admin' => __('users::app.Admin'),
                'faculty_member' => __('users::app.Faculty Member'),
                'student' => __('users::app.Student'),
                'staff' => __('users::app.Staff'),
            ]);

        return $filters;
    }
}
