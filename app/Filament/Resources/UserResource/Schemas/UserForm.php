<?php

namespace App\Filament\Resources\UserResource\Schemas;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\University;
use Filament\Forms;
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
            Forms\Components\Section::make('Account Information')
                ->schema([
                    Forms\Components\TextInput::make('username')
                        ->label('Username')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->required(fn ($livewire) => $livewire instanceof CreateUser)
                        ->dehydrated(fn ($state) => filled($state))
                        ->helperText(fn ($livewire) => $livewire instanceof CreateUser 
                            ? null 
                            : 'Leave empty to keep current password'),
                ])
                ->columns(3),

            // Personal Information Section
            Forms\Components\Section::make('Personal Information')
                ->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->label('First Name')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('last_name')
                        ->label('Last Name')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('display_name')
                        ->label('Display Name')
                        ->maxLength(255)
                        ->helperText('This name will be shown in the admin panel'),
                ])
                ->columns(3),

            // Role and Access Section - Only visible to admins
            Forms\Components\Section::make('Role & Access Control')
                ->description('Define user role and scope of access')
                ->schema([
                    Forms\Components\Toggle::make('is_admin')
                        ->label('Administrator')
                        ->helperText('Administrators have global access to all universities')
                        ->live()
                        ->afterStateUpdated(function (Set $set, $state) {
                            if ($state) {
                                // Clear scope fields when set to admin
                                $set('university_id', null);
                                $set('faculty_id', null);
                                $set('subject_id', null);
                                $set('scope_level', 'admin');
                            }
                        })
                        ->columnSpanFull(),

                    Forms\Components\Select::make('role')
                        ->label('Legacy Role')
                        ->options([
                            'admin' => 'Admin',
                            'faculty_member' => 'Faculty Member',
                            'student' => 'Student',
                            'staff' => 'Staff',
                        ])
                        ->default('faculty_member'),

                    // Scope Level Selection
                    Forms\Components\Select::make('scope_level')
                        ->label('Scope Level')
                        ->options([
                            'university' => 'University Level - Access to all faculties within a university',
                            'faculty' => 'Faculty Level - Access to all departments/subjects within a faculty',
                            'subject' => 'Subject Level - Access only to a specific subject',
                        ])
                        ->live()
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
            Forms\Components\Section::make('Academic Scope Assignment')
                ->description('Assign the user to a specific academic scope')
                ->schema([
                    // University Select
                    Forms\Components\Select::make('university_id')
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
                        ->required(fn (Get $get) => !$get('is_admin') && in_array($get('scope_level'), ['university', 'faculty', 'subject'])),

                    // Faculty Select
                    Forms\Components\Select::make('faculty_id')
                        ->label('Faculty')
                        ->options(function (Get $get) use ($currentUser) {
                            $universityId = $get('university_id');
                            
                            if ($currentUser->isAdmin()) {
                                if ($universityId) {
                                    return Faculty::where('university_id', $universityId)->pluck('name', 'id');
                                }
                                return [];
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
                    Forms\Components\Select::make('subject_id')
                        ->label('Subject')
                        ->options(function (Get $get) use ($currentUser) {
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

            // Info Card for Non-Admin Users
            Forms\Components\Section::make('Scope Information')
                ->schema([
                    Forms\Components\Placeholder::make('scope_info')
                        ->label('Your Access Scope')
                        ->content(fn () => $currentUser->scope_description ?? 'No scope assigned'),
                ])
                ->visible(fn () => !$isAdmin),
        ];
    }
}
