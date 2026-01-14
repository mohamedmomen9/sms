<?php

namespace Modules\Subject\Filament\Resources\SubjectResource\Schemas;

use App\Filament\Forms\Components\TranslatableInput;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

class SubjectForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return [
            Section::make(__('subject::app.Academic Assignment'))
                ->description(__('subject::app.Select the academic hierarchy for this subject'))
                ->schema([
                    Select::make('faculty_id')
                        ->label(__('subject::app.Faculty'))
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
                        ->disabled(fn () => !$user->isAdmin() && ($user->isScopedToFaculty() || $user->isScopedToSubject()))
                        ->visible(fn () => !$user->isScopedToSubject()),

                    Select::make('department_id')
                        ->label(__('subject::app.Department (Optional)'))
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
                        ->helperText(__('subject::app.Leave empty if subject belongs directly to faculty'))
                        ->visible(fn () => !$user->isScopedToSubject()),
                ])
                ->columns(2),

            Section::make(__('subject::app.Subject Details'))
                ->schema([
                    TranslatableInput::make('name', TextInput::class, function ($field, $locale) {
                        return $field->required()->maxLength(255)->label(__('subject::app.Subject Name'));
                    }),

                    TextInput::make('code')
                        ->label(__('subject::app.Subject Code'))
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                ])
                ->columns(2),
        ];
    }
}
