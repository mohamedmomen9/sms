<?php

namespace App\Filament\Resources\UserResource\Tables;

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
                ->label(__('app.Username'))
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('email')
                ->label(__('app.Email'))
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('display_name')
                ->label(__('app.Display Name'))
                ->searchable()
                ->sortable()
                ->toggleable(),

            IconColumn::make('is_admin')
                ->label(__('app.Admin'))
                ->boolean()
                ->trueIcon('heroicon-o-shield-check')
                ->falseIcon('heroicon-o-user')
                ->color(fn (bool $state): string => $state ? 'success' : 'gray')
                ->alignCenter(),

            TextColumn::make('roles.name')
                ->label(__('app.Roles'))
                ->badge()
                ->separator(', ')
                ->sortable(),

            TextColumn::make('created_at')
                ->label(__('app.Created'))
                ->dateTime('M d, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            TernaryFilter::make('is_admin')
                ->label(__('app.Administrator'))
                ->trueLabel(__('app.Admins Only'))
                ->falseLabel(__('app.Non-Admins Only')),

            SelectFilter::make('role')
                ->label(__('app.Role'))
                ->options([
                    'admin' => __('app.Admin'),
                    'faculty_member' => __('app.Faculty Member'),
                    'student' => __('app.Student'),
                    'staff' => __('app.Staff'),
                ]),
        ];
    }
}
