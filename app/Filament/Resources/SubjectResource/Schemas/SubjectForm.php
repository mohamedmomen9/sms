<?php

namespace App\Filament\Resources\SubjectResource\Schemas;

use App\Filament\Forms\Components\TranslatableInput;
use App\Models\Curriculum;
use App\Models\Department;
use App\Models\Faculty;
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
            Section::make(__('app.Academic Assignment'))
                ->description(__('app.Select the academic hierarchy for this subject'))
                ->schema([
                    // Faculty Select
                    Select::make('faculty_id')
                        ->label(__('app.Faculty'))
                        ->relationship('faculty', 'name')
                        ->options(function () use ($user) {
                            if ($user->isAdmin()) {
                                return Faculty::pluck('name', 'id');
                            }
                            
                            if ($user->isScopedToFaculty() || $user->isScopedToSubject()) {
                                $facultyId = $user->getScopedFacultyId();
                                return Faculty::where('id', $facultyId)->pluck('name', 'id');
                            }
                            
                            return Faculty::pluck('name', 'id');
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
                        ->label(__('app.Department (Optional)'))
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
                        ->helperText(__('app.Leave empty if subject belongs directly to faculty'))
                        ->visible(fn () => !$user->isScopedToSubject())
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('curriculum_id', null);
                        }),

                    // Curriculum Select - Filtered by Department
                    Select::make('curriculum_id')
                        ->label(__('app.Curriculum Group'))
                        ->relationship('curriculumGroup', 'name')
                        ->options(function (Get $get) {
                            $departmentId = $get('department_id');
                            if ($departmentId) {
                                return Curriculum::where('department_id', $departmentId)->pluck('name', 'id');
                            }
                            return Curriculum::pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label(__('app.Name'))
                                ->required(),
                            TextInput::make('code')
                                ->label(__('app.Code')),
                        ])
                        ->createOptionUsing(function (array $data, Get $get) {
                            $departmentId = $get('department_id');
                            if (!$departmentId) {
                                return null; 
                            }
                            return Curriculum::create([
                                'department_id' => $departmentId,
                                'name' => $data['name'],
                                'code' => $data['code'] ?? null,
                            ])->id;
                        }),
                ])
                ->columns(2),

            // Subject Details Section
            Section::make(__('app.Subject Details'))
                ->schema([
                    TextInput::make('curriculum')
                        ->label(__('app.Legacy Curriculum String'))
                        ->helperText(__('app.This field is deprecated. Please use Curriculum Group.'))
                        ->required()
                        ->maxLength(255),

                    TextInput::make('code')
                        ->label(__('app.Subject Code'))
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                ])
                ->columns(2),

            // Translatable Names Section
            Section::make(__('app.Subject Names'))
                ->schema([
                    TranslatableInput::make('name', TextInput::class, function ($field, $locale) {
                        return $field->required()->maxLength(255);
                    }),
                ])
                ->columns(2),

            // Additional Properties Section
            Section::make(__('app.Additional Properties'))
                ->schema([
                    Select::make('category')
                        ->label(__('app.Category'))
                        ->options([
                            'core' => __('app.Core'),
                            'elective' => __('app.Elective'),
                            'general' => __('app.General'),
                            'specialization' => __('app.Specialization'),
                        ])
                        ->nullable(),

                    Select::make('type')
                        ->label(__('app.Type'))
                        ->options([
                            'theoretical' => __('app.Theoretical'),
                            'practical' => __('app.Practical'),
                            'mixed' => __('app.Mixed'),
                        ])
                        ->nullable(),

                    TextInput::make('max_hours')
                        ->label(__('app.Maximum Hours'))
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->step(0.5),
                ])
                ->columns(3),
        ];
    }
}
