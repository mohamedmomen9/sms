<?php

namespace App\Filament\Resources\UserResource\Schemas;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\University;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
            Section::make('Account Information')
                ->schema([
                    TextInput::make('username')
                        ->label('Username')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('password')
                        ->password()
                        ->required(fn ($livewire) => $livewire instanceof CreateUser)
                        ->dehydrated(fn ($state) => filled($state))
                        ->helperText(fn ($livewire) => $livewire instanceof CreateUser 
                            ? null 
                            : 'Leave empty to keep current password'),
                ])
                ->columns(3),

            // Personal Information Section
            Section::make('Personal Information')
                ->schema([
                    TextInput::make('first_name')
                        ->label('First Name')
                        ->maxLength(255),

                    TextInput::make('last_name')
                        ->label('Last Name')
                        ->maxLength(255),

                    TextInput::make('display_name')
                        ->label('Display Name')
                        ->maxLength(255)
                        ->helperText('This name will be shown in the admin panel'),
                ])
                ->columns(3),

            // Role and Access Section - Only visible to admins
            Section::make('Role & Access Control')
                ->description('Define user role and scope of access')
                ->schema([
                    Toggle::make('is_admin')
                        ->label('Administrator')
                        ->helperText('Administrators have global access to all universities')
                        ->live()
                        ->afterStateUpdated(function (Set $set, $state) {
                            if ($state) {
                                // Clear scope fields when set to admin
                                $set('university_id', null);
                                $set('faculty_id', null);
                                $set('subject_id', null);
                                $set('scope_level', null);
                            }
                        })
                        ->columnSpanFull(),

                    Select::make('role')
                        ->label('User Role')
                        ->options([
                            'admin' => 'Admin',
                            'faculty_member' => 'Faculty Member',
                            'student' => 'Student',
                            'staff' => 'Staff',
                        ])
                        ->default('faculty_member'),

                    // Scope Level Selection - Calculate default from record
                    Select::make('scope_level')
                        ->label('Scope Level')
                        ->options([
                            'university' => 'University Level - Access to all faculties within a university',
                            'faculty' => 'Faculty Level - Access to all departments/subjects within a faculty',
                            'subject' => 'Subject Level - Access only to a specific subject',
                        ])
                        ->live()
                        ->default(function ($record) {
                            // Calculate scope level from existing record
                            if (!$record) {
                                return null;
                            }
                            if ($record->subject_id) {
                                return 'subject';
                            }
                            if ($record->faculty_id) {
                                return 'faculty';
                            }
                            if ($record->university_id) {
                                return 'university';
                            }
                            return null;
                        })
                        ->afterStateHydrated(function (Select $component, $record) {
                            // Set scope level when editing
                            if ($record) {
                                if ($record->subject_id) {
                                    $component->state('subject');
                                } elseif ($record->faculty_id) {
                                    $component->state('faculty');
                                } elseif ($record->university_id) {
                                    $component->state('university');
                                }
                            }
                        })
                        ->afterStateUpdated(function (Set $set, $state) {
                            // Clear lower-level selections when scope changes
                            if ($state === 'university') {
                                $set('faculty_id', null);
                                $set('subject_id', null);
                            } elseif ($state === 'faculty') {
                                $set('subject_id', null);
                            }
                        })
                        ->dehydrated(false)
                        ->visible(fn (Get $get) => !$get('is_admin'))
                        ->helperText('Select the level of access for this user'),
                ])
                ->columns(2)
                ->visible(fn () => $isAdmin),

            // Academic Scope Assignment Section
            Section::make('Academic Scope Assignment')
                ->description('Assign the user to a specific academic scope')
                ->schema([
                    // University Select
                    Select::make('university_id')
                        ->label('University')
                        ->options(function () use ($currentUser) {
                            if ($currentUser->isAdmin()) {
                                return University::pluck('name', 'id');
                            }
                            $universityId = $currentUser->getScopedUniversityId();
                            if ($universityId) {
                                return University::where('id', $universityId)->pluck('name', 'id');
                            }
                            return [];
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('faculty_id', null);
                            $set('subject_id', null);
                        })
                        ->visible(fn (Get $get) => !$get('is_admin') && in_array($get('scope_level'), ['university', 'faculty', 'subject']))
                        ->required(fn (Get $get) => !$get('is_admin') && $get('scope_level') === 'university'),

                    // Faculty Select
                    Select::make('faculty_id')
                        ->label('Faculty')
                        ->options(function (Get $get) use ($currentUser) {
                            $universityId = $get('university_id');
                            
                            if ($currentUser->isAdmin()) {
                                if ($universityId) {
                                    return Faculty::where('university_id', $universityId)->pluck('name', 'id');
                                }
                                return Faculty::pluck('name', 'id');
                            }
                            
                            // For scoped admin users
                            $accessibleFacultyIds = $currentUser->getAccessibleFacultyIds();
                            if ($universityId) {
                                return Faculty::where('university_id', $universityId)
                                    ->whereIn('id', $accessibleFacultyIds)
                                    ->pluck('name', 'id');
                            }
                            
                            return Faculty::whereIn('id', $accessibleFacultyIds)->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('subject_id', null);
                        })
                        ->visible(fn (Get $get) => !$get('is_admin') && in_array($get('scope_level'), ['faculty', 'subject']))
                        ->required(fn (Get $get) => !$get('is_admin') && in_array($get('scope_level'), ['faculty', 'subject'])),

                    // Subject Select
                    Select::make('subject_id')
                        ->label('Subject')
                        ->options(function (Get $get) {
                            $facultyId = $get('faculty_id');
                            
                            if (!$facultyId) {
                                return [];
                            }
                            
                            // Get subjects for the selected faculty
                            return Subject::where(function ($query) use ($facultyId) {
                                $query->where('faculty_id', $facultyId)
                                    ->orWhereHas('department', function ($q) use ($facultyId) {
                                        $q->where('faculty_id', $facultyId);
                                    });
                            })->get()->mapWithKeys(function ($subject) {
                                $name = $subject->name_en ?? $subject->name_ar ?? $subject->code;
                                return [$subject->id => "{$subject->code} - {$name}"];
                            });
                        })
                        ->searchable()
                        ->preload()
                        ->visible(fn (Get $get) => !$get('is_admin') && $get('scope_level') === 'subject')
                        ->required(fn (Get $get) => !$get('is_admin') && $get('scope_level') === 'subject'),
                ])
                ->columns(3)
                ->visible(fn (Get $get) => $isAdmin && !$get('is_admin')),

            // Roles assignment
            Section::make('System Roles')
                ->description('Assign Spatie roles for permissions')
                ->schema([
                    Select::make('roles')
                        ->label('Roles')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                ])
                ->visible(fn () => $isAdmin),

            // Info Card for Non-Admin Users
            Section::make('Scope Information')
                ->schema([
                    Placeholder::make('scope_info')
                        ->label('Your Access Scope')
                        ->content(fn () => $currentUser->scope_description ?? 'No scope assigned'),
                ])
                ->visible(fn () => !$isAdmin),
        ];
    }
}
