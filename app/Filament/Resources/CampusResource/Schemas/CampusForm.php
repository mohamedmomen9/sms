<?php

namespace App\Filament\Resources\CampusResource\Schemas;

use App\Models\University;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class CampusForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user?->isAdmin() ?? false;

        return [
            // University Assignment Section
            Section::make('University Assignment')
                ->description('Select the university for this campus')
                ->schema([
                    Select::make('university_id')
                        ->label('University')
                        ->relationship('university', 'name')
                        ->options(function () use ($user) {
                            if ($user->isAdmin()) {
                                return University::pluck('name', 'id');
                            }
                            $universityId = $user->getScopedUniversityId();
                            if ($universityId) {
                                return University::where('id', $universityId)->pluck('name', 'id');
                            }
                            return [];
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->default(function () use ($user) {
                            if (!$user->isAdmin()) {
                                return $user->getScopedUniversityId();
                            }
                            return null;
                        })
                        ->disabled(fn () => !$isAdmin && $user->getScopedUniversityId() !== null),
                ])
                ->visible(fn () => $isAdmin || $user->isScopedToUniversity()),

            // Hidden university for auto-assignment
            Hidden::make('university_id')
                ->default(function () use ($user) {
                    if (!$user->isAdmin()) {
                        return $user->getScopedUniversityId();
                    }
                    return null;
                })
                ->visible(fn () => !$isAdmin && !$user->isScopedToUniversity() && $user->getScopedUniversityId() !== null),

            // Campus Details Section
            Section::make('Campus Details')
                ->schema([
                    TextInput::make('code')
                        ->label('Campus Code')
                        ->required()
                        ->maxLength(50)
                        ->helperText('Unique code within the university'),

                    TextInput::make('name')
                        ->label('Campus Name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('location')
                        ->label('Location')
                        ->maxLength(255)
                        ->placeholder('e.g., Main City, North District'),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->required()
                        ->default('active'),
                ])
                ->columns(2),

            // Contact Information Section
            Section::make('Contact Information')
                ->schema([
                    Textarea::make('address')
                        ->label('Full Address')
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpanFull(),

                    TextInput::make('phone')
                        ->label('Phone Number')
                        ->tel()
                        ->maxLength(50),

                    TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->maxLength(255),
                ])
                ->columns(2)
                ->collapsible(),
        ];
    }
}
