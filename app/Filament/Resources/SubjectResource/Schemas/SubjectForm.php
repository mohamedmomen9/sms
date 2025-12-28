<?php

namespace App\Filament\Resources\SubjectResource\Schemas;

use App\Filament\Forms\Components\TranslatableInput;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\University;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

class SubjectForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user?->isAdmin() ?? false;

        return [
            // Section for Academic Hierarchy Selection
            Section::make('Academic Assignment')
                ->description('Select the academic hierarchy for this subject')
                ->schema([
                    // University Select - For filtering purposes
                    Select::make('university_id')
                        ->label('University')
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
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('faculty_id', null);
                            $set('department_id', null);
                        })
                        ->default(function () use ($user) {
                            if (!$user->isAdmin()) {
                                return $user->getScopedUniversityId();
                            }
                            return null;
                        })
                        ->disabled(fn () => !$isAdmin && $user->getScopedUniversityId() !== null)
                        ->dehydrated(false)
                        ->visible(fn () => $isAdmin || $user->isScopedToUniversity()),

                    // Faculty Select - Filtered by university
                    Select::make('faculty_id')
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
                            
                            if ($user->isScopedToFaculty() || $user->isScopedToSubject()) {
                                $facultyId = $user->getScopedFacultyId();
                                return Faculty::where('id', $facultyId)->pluck('name', 'id');
                            }
                            
                            if ($user->isScopedToUniversity()) {
                                return Faculty::where('university_id', $user->university_id)->pluck('name', 'id');
                            }
                            
                            return [];
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('department_id', null);
                        })
                        ->default(function () use ($user) {
                            if ($user->isScopedToFaculty() || $user->isScopedToSubject()) {
                                return $user->getScopedFacultyId();
                            }
                            return null;
                        })
                        ->disabled(fn () => $user->isScopedToFaculty() || $user->isScopedToSubject())
                        ->visible(fn () => !$user->isScopedToSubject()),

                    // Department Select - Optional, filtered by faculty
                    Select::make('department_id')
                        ->label('Department (Optional)')
                        ->relationship('department', 'name')
                        ->options(function (Get $get) use ($user) {
                            $facultyId = $get('faculty_id');
                            
                            if ($user->isAdmin()) {
                                if ($facultyId) {
                                    return Department::where('faculty_id', $facultyId)->pluck('name', 'id');
                                }
                                return Department::pluck('name', 'id');
                            }
                            
                            $accessibleFacultyIds = $user->getAccessibleFacultyIds();
                            if ($facultyId && in_array($facultyId, $accessibleFacultyIds)) {
                                return Department::where('faculty_id', $facultyId)->pluck('name', 'id');
                            }
                            
                            return Department::whereIn('faculty_id', $accessibleFacultyIds)->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->helperText('Leave empty if subject belongs directly to faculty')
                        ->visible(fn () => !$user->isScopedToSubject()),
                ])
                ->columns(3),

            // Subject Details Section
            Section::make('Subject Details')
                ->schema([
                    TextInput::make('curriculum')
                        ->label('Curriculum / Group')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('code')
                        ->label('Subject Code')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                ])
                ->columns(2),

            // Translatable Names Section
            Section::make('Subject Names')
                ->schema([
                    TranslatableInput::make('name', TextInput::class, function ($field, $locale) {
                        return $field->required()->maxLength(255);
                    }),
                ])
                ->columns(2),

            // Additional Properties Section
            Section::make('Additional Properties')
                ->schema([
                    Select::make('category')
                        ->label('Category')
                        ->options([
                            'core' => 'Core',
                            'elective' => 'Elective',
                            'general' => 'General',
                            'specialization' => 'Specialization',
                        ])
                        ->nullable(),

                    Select::make('type')
                        ->label('Type')
                        ->options([
                            'theoretical' => 'Theoretical',
                            'practical' => 'Practical',
                            'mixed' => 'Mixed',
                        ])
                        ->nullable(),

                    TextInput::make('max_hours')
                        ->label('Maximum Hours')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->step(0.5),
                ])
                ->columns(3),
        ];
    }
}
