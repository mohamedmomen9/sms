<?php

namespace App\Filament\Resources\UniversityResource\Schemas;

use Filament\Forms;
use Illuminate\Support\Facades\Auth;

class UniversityForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user?->isAdmin() ?? false;

        return [
            // University Details Section
            Forms\Components\Section::make('University Details')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('University Code')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->disabled(fn () => !$isAdmin),

                    Forms\Components\TextInput::make('name')
                        ->label('University Name')
                        ->required()
                        ->maxLength(255)
                        ->disabled(fn () => !$isAdmin),
                ])
                ->columns(2),

            // Logo Section
            Forms\Components\Section::make('Branding')
                ->schema([
                    Forms\Components\FileUpload::make('logo')
                        ->label('University Logo')
                        ->image()
                        ->imageEditor()
                        ->directory('universities/logos')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->helperText('Upload a logo (max 2MB, recommended 200x200 pixels)')
                        ->disabled(fn () => !$isAdmin),
                ])
                ->collapsible(),

            // Info placeholder for non-admins
            Forms\Components\Section::make('Access Information')
                ->schema([
                    Forms\Components\Placeholder::make('access_info')
                        ->label('')
                        ->content('You are viewing this university as a standard user. Only administrators can modify university details.'),
                ])
                ->visible(fn () => !$isAdmin),
        ];
    }
}
