<?php

namespace App\Filament\Resources\DepartmentResource\Schemas;

use App\Models\Faculty;
use App\Models\University;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

class DepartmentForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user?->isAdmin() ?? false;

        return [
            // Section for Academic Hierarchy Selection
            Forms\Components\Section::make('Academic Assignment')
                ->description('Select the university and faculty for this department')
                ->schema([
                    // University Select - Only visible to admins
                    Forms\Components\Select::make('university_id')
                        ->label('University')
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
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            // Reset faculty when university changes
                            $set('faculty_id', null);
                        })
                        ->default(function () use ($user) {
                            // Auto-set for scoped users
                            if (!$user->isAdmin()) {
                                return $user->getScopedUniversityId();
                            }
                            return null;
                        })
                        ->disabled(fn () => !$isAdmin && $user->getScopedUniversityId() !== null)
                        ->dehydrated(false) // Don't save this field, it's for filtering only
                        ->visible(fn () => $isAdmin || $user->isScopedToUniversity()),

                    // Faculty Select - Filtered by selected university
                    Forms\Components\Select::make('faculty_id')
                        ->label('Faculty')
                        ->relationship('faculty', 'name')
                        ->options(function (Get $get) use ($user) {
                            $universityId = $get('university_id');
                            
                            if ($user->isAdmin()) {
                                if ($universityId) {
                                    return Faculty::where('university_id', $universityId)->pluck('name', 'id');
                                }
                                return Faculty::pluck('name', 'id');
                            }
                            
                            // For faculty-scoped users, only show their faculty
                            if ($user->isScopedToFaculty()) {
                                return Faculty::where('id', $user->faculty_id)->pluck('name', 'id');
                            }
                            
                            // For university-scoped users, show faculties in their university
                            if ($user->isScopedToUniversity()) {
                                return Faculty::where('university_id', $user->university_id)->pluck('name', 'id');
                            }
                            
                            return [];
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->default(function () use ($user) {
                            if ($user->isScopedToFaculty()) {
                                return $user->faculty_id;
                            }
                            return null;
                        })
                        ->disabled(fn () => $user->isScopedToFaculty()),
                ])
                ->columns(2),

            // Section for Department Details
            Forms\Components\Section::make('Department Details')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Department Code')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('name')
                        ->label('Department Name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'pending' => 'Pending',
                        ])
                        ->required()
                        ->default('active'),
                ])
                ->columns(3),
        ];
    }
}
