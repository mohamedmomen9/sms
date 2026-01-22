<?php

namespace Modules\Users\Filament\Resources\UserResource\Schemas;

use Modules\Users\Filament\Resources\UserResource\Pages\CreateUser;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;
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
        /** @var \Modules\Users\Models\User $currentUser */
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->hasPermissionTo('scope:global') ?? false;

        return [
            Section::make(__('users::app.Account Information'))
                ->schema([
                    TextInput::make('username')
                        ->label(__('users::app.Username'))
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
                        ->required(fn($livewire) => $livewire instanceof CreateUser)
                        ->dehydrated(fn($state) => filled($state))
                        ->helperText(fn($livewire) => $livewire instanceof CreateUser
                            ? null
                            : __('users::app.Leave empty password')),
                ])
                ->columns(3),

            Section::make(__('users::app.Personal Information'))
                ->schema([
                    TextInput::make('first_name')
                        ->label(__('users::app.First Name'))
                        ->maxLength(255),

                    TextInput::make('last_name')
                        ->label(__('users::app.Last Name'))
                        ->maxLength(255),

                    TextInput::make('display_name')
                        ->label(__('users::app.Display Name'))
                        ->maxLength(255)
                        ->helperText(__('users::app.Display Name Helper')),
                ])
                ->columns(3),

            Section::make(__('users::app.Role & Access Control'))
                ->description(__('users::app.Assign Roles Description'))
                ->schema([
                    Select::make('roles')
                        ->label(__('users::app.Roles'))
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->default([])
                        ->live()
                        ->afterStateUpdated(function (Set $set) {}),
                ])
                ->visible(fn() => $isAdmin),

            Section::make(__('users::app.Academic Scope Assignment'))
                ->description(__('users::app.Scope Info Description'))
                ->schema([
                    Select::make('faculty_id')
                        ->label(__('users::app.Faculty'))
                        ->options(function (Get $get) use ($currentUser) {
                            if ($currentUser->can('scope:global')) {
                                return Faculty::all()->pluck('name', 'id');
                            }

                            $accessibleFacultyIds = $currentUser->getAccessibleFacultyIds();
                            return Faculty::whereIn('id', $accessibleFacultyIds)->get()->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('subject_id', null);
                        })
                        ->visible(fn(Get $get) => self::hasScopePermission($get('roles'), ['scope:faculty', 'scope:subject']))
                        ->required(fn(Get $get) => self::hasScopePermission($get('roles'), ['scope:faculty', 'scope:subject'])),

                    Select::make('subjects')
                        ->label(__('users::app.Subjects'))
                        ->relationship('subjects', 'code', modifyQueryUsing: function ($query, Get $get) {
                            $facultyId = $get('faculty_id');
                            if (!$facultyId) {
                                return $query->whereRaw('1 = 0');
                            }
                            return $query->where('faculty_id', $facultyId);
                        })
                        ->getOptionLabelFromRecordUsing(fn(Subject $record) => "{$record->code} - {$record->name}")
                        ->multiple()
                        ->preload()
                        ->searchable(['name->en', 'name->ar', 'code'])
                        ->visible(fn(Get $get) => self::hasScopePermission($get('roles'), ['scope:subject']))
                        ->required(fn(Get $get) => self::hasScopePermission($get('roles'), ['scope:subject']))
                        ->rules([
                            function (Get $get, $livewire) use ($currentUser) {
                                return function (string $attribute, $value, \Closure $fail) use ($currentUser, $livewire) {
                                    if (empty($value) || !is_array($value)) return;

                                    $subjects = Subject::whereIn('id', $value)->with('prerequisites')->get();

                                    $passedSubjectIds = [];
                                    $targetUser = $livewire->record ?? null;

                                    if ($targetUser) {
                                        $passedSubjectIds = $targetUser->subjects()
                                            ->wherePivot('status', 'completed')
                                            ->pluck('subjects.id')
                                            ->toArray();
                                    }

                                    foreach ($subjects as $subject) {
                                        foreach ($subject->prerequisites as $prereq) {
                                            if (!in_array($prereq->id, $passedSubjectIds)) {
                                                $fail(__('users::app.Prerequisite Error', ['subject' => $subject->name, 'prerequisite' => $prereq->name]));
                                            }
                                        }
                                    }
                                };
                            }
                        ]),
                ])
                ->columns(3)
                ->visible(fn() => $isAdmin),

            Section::make(__('users::app.Scope Information'))
                ->schema([
                    Placeholder::make('scope_info')
                        ->label(__('users::app.Your Access Scope'))
                        ->content(fn() => $currentUser->scope_description ?? 'No scope assigned'),
                ])
                ->visible(fn() => !$isAdmin),
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
