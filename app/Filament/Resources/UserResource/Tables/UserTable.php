<?php

namespace App\Filament\Resources\UserResource\Tables;

use App\Models\Faculty;
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

            TextColumn::make('scope_type')
                ->label(__('app.Scope'))
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Admin (Global Access)' => 'success',
                    'University' => 'primary',
                    'Faculty' => 'info',
                    'Subject' => 'warning',
                    default => 'gray',
                }),

            TextColumn::make('faculty.name')
                ->label(__('app.Faculty'))
                ->sortable()
                ->toggleable()
                ->placeholder('-'),

            TextColumn::make('subject.name')
                ->label(__('app.Subject'))
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->placeholder('-'),

            TextColumn::make('role')
                ->label(__('app.Role'))
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $filters = [];

        $filters[] = TernaryFilter::make('is_admin')
            ->label(__('app.Administrator'))
            ->trueLabel(__('app.Admins Only'))
            ->falseLabel(__('app.Non-Admins Only'));

        $filters[] = SelectFilter::make('faculty_id')
            ->label(__('app.Faculty'))
            ->options(Faculty::all()->pluck('name', 'id'))
            ->searchable();

        $filters[] = SelectFilter::make('role')
            ->label(__('app.Role'))
            ->options([
                'admin' => __('app.Admin'),
                'faculty_member' => __('app.Faculty Member'),
                'student' => __('app.Student'),
                'staff' => __('app.Staff'),
            ]);

        return $filters;
    }
}
