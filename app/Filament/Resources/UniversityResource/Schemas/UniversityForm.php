<?php

namespace App\Filament\Resources\UniversityResource\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
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
            Section::make('University Details')
                ->schema([
                    TextInput::make('code')
                        ->label('University Code')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->disabled(fn() => !$isAdmin),

                    TextInput::make('name')
                        ->label('University Name')
                        ->required()
                        ->maxLength(255)
                        ->disabled(fn() => !$isAdmin),
                ])
                ->columns(2),

            // Logo Section
            Section::make('Branding')
                ->schema([
                    FileUpload::make('logo')
                        ->label('University Logo')
                        ->image()
                        ->imageEditor()
                        ->directory('universities/logos')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->helperText('Upload a logo (max 2MB, recommended 200x200 pixels)')
                        ->disabled(fn() => !$isAdmin),
                ])
                ->collapsible(),

            // Info placeholder for non-admins
            Section::make('Access Information')
                ->schema([
                    Placeholder::make('access_info')
                        ->label('')
                        ->content('You are viewing this university as a standard user. Only administrators can modify university details.'),
                ])
                ->visible(fn() => !$isAdmin),
        ];
    }
}
