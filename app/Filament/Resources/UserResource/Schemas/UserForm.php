<?php

namespace App\Filament\Resources\UserResource\Schemas;

use App\Filament\Resources\UserResource\Pages\CreateUser; // Correct import
use Filament\Forms;

class UserForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('username')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof CreateUser)
                ->dehydrated(fn ($state) => filled($state)),
            Forms\Components\Select::make('role')
                ->options([
                    'admin' => 'Admin',
                    'faculty_member' => 'Faculty Member',
                    'student' => 'Student',
                ]),
            Forms\Components\Select::make('faculty_id')
                ->relationship('faculty', 'name')
                ->label('Faculty')
                ->placeholder('Select Faculty (Optional)'),
        ];
    }
}
