<?php

namespace App\Filament\Resources\UserResource\Schemas;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\Faculty;
use App\Models\Subject;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

class UserForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->hasPermissionTo('scope:global') ?? false;

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
            // Role and Access Section
            Section::make('Role & Access Control')
                ->description('Assign Roles to define permissions and access scope')
                ->schema([
                    Select::make('roles')
                        ->label('Roles')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->default([])
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            // Reset scopes if roles change
                            // We might want to be smarter here, but safety first
                            // $set('university_id', null);
                            // $set('faculty_id', null);
                            // $set('subject_id', null);
                        }),
                ])
                ->visible(fn () => $isAdmin),

            // Academic Scope Assignment Section
            Section::make('Academic Scope Assignment')
                ->description('Assign the user to a specific academic scope based on their Role')
                ->schema([
                    // Faculty Select
                    Select::make('faculty_id')
                        ->label('Faculty')
                        ->options(function (Get $get) use ($currentUser) {
                            if ($currentUser->can('scope:global')) {
                                return Faculty::all()->pluck('name', 'id');
                            }
                            
                            // For scoped users
                            $accessibleFacultyIds = $currentUser->getAccessibleFacultyIds();
                            return Faculty::whereIn('id', $accessibleFacultyIds)->get()->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('subject_id', null);
                        })
                        ->visible(fn (Get $get) => self::hasScopePermission($get('roles'), ['scope:faculty', 'scope:subject']))
                        ->required(fn (Get $get) => self::hasScopePermission($get('roles'), ['scope:faculty', 'scope:subject'])),

                    // Subject Select
                    Select::make('subjects')
                        ->label('Subjects')
                        ->relationship('subjects', 'code', modifyQueryUsing: function ($query, Get $get) {
                            $facultyId = $get('faculty_id');
                            if (!$facultyId) {
                                // If no faculty selected, return no results (or all? Usually none until filtered)
                                return $query->whereRaw('1 = 0');
                            }
                            return $query->where('faculty_id', $facultyId);
                        })
                        ->getOptionLabelFromRecordUsing(fn (Subject $record) => "{$record->code} - {$record->name}")
                        ->multiple()
                        ->preload()
                        ->searchable(['name->en', 'name->ar', 'code'])
                        ->visible(fn (Get $get) => self::hasScopePermission($get('roles'), ['scope:subject']))
                        ->required(fn (Get $get) => self::hasScopePermission($get('roles'), ['scope:subject'])),
                ])
                ->columns(3)
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

    protected static function hasScopePermission($roleIds, array $permissions): bool
    {
        if (empty($roleIds)) {
            return false;
        }

        if ($roleIds instanceof \Illuminate\Support\Collection) {
            $roleIds = $roleIds->toArray();
        }

        return \App\Models\Role::whereIn('id', (array) $roleIds)
            ->whereHas('permissions', function ($query) use ($permissions) {
                $query->whereIn('name', $permissions);
            })
            ->exists();
    }
}
