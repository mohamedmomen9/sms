<?php

namespace App\Filament\Resources\FacultyResource\Schemas;

use App\Models\University;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;

class FacultyForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user?->isAdmin() ?? false;

        return [
            // Section for University Assignment
            Forms\Components\Section::make('University Assignment')
                ->description('Select the university for this faculty')
                ->schema([
                    Forms\Components\Select::make('university_id')
                        ->label('University')
                        ->relationship('university', 'name')
                        ->options(function () use ($user) {
                            if ($user->isAdmin()) {
                                return University::pluck('name', 'id');
                            }
                            // For scoped users, only show their university
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

            // Hidden field for auto-assignment when user is scoped
            Forms\Components\Hidden::make('university_id')
                ->default(function () use ($user) {
                    if (!$user->isAdmin()) {
                        return $user->getScopedUniversityId();
                    }
                    return null;
                })
                ->visible(fn () => !$isAdmin && !$user->isScopedToUniversity() && $user->getScopedUniversityId() !== null),

            // Section for Faculty Details
            Forms\Components\Section::make('Faculty Details')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Faculty Code')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('name')
                        ->label('Faculty Name')
                        ->required()
                        ->maxLength(255),
                ])
                ->columns(2),
        ];
    }
}
