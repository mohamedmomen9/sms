<?php

namespace App\Filament\Resources\UserResource\Schemas;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class UserForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->isAdmin() ?? false;

        return [
            // Basic Information Section
            Section::make(__('app.Account Information'))
                ->schema([
                    TextInput::make('username')
                        ->label(__('app.Username'))
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('email')
                        ->label(__('app.Email'))
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('password')
                        ->label(__('app.Password'))
                        ->password()
                        ->required(fn ($livewire) => $livewire instanceof CreateUser)
                        ->dehydrated(fn ($state) => filled($state))
                        ->helperText(fn ($livewire) => $livewire instanceof CreateUser 
                            ? null 
                            : 'Leave empty to keep current password'),
                ])
                ->columns(3),

            // Personal Information Section
            Section::make(__('app.Personal Information'))
                ->schema([
                    TextInput::make('first_name')
                        ->label(__('app.First Name'))
                        ->maxLength(255),

                    TextInput::make('last_name')
                        ->label(__('app.Last Name'))
                        ->maxLength(255),

                    TextInput::make('display_name')
                        ->label(__('app.Display Name'))
                        ->maxLength(255)
                        ->helperText('This name will be shown in the admin panel'),
                ])
                ->columns(3),

            // Role and Access Section
            Section::make(__('app.Role & Access Control'))
                ->description('Assign Roles to define permissions and access scope')
                ->schema([
                    Select::make('roles')
                        ->label(__('app.Roles'))
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->default([]),
                ])
                ->visible(fn () => $isAdmin),
        ];
    }
}
