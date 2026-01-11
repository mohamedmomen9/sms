<?php

namespace Modules\Students\Filament\Resources\StudentResource\Schemas;

use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class StudentForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create'),
            Forms\Components\TextInput::make('student_id')
                ->label('Student ID')
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Forms\Components\DatePicker::make('date_of_birth'),
            Forms\Components\Select::make('campus_id')
                ->relationship('campus', 'name')
                ->live()
                ->afterStateUpdated(function (Set $set) {
                    $set('school_id', null);
                    $set('department_id', null);
                    $set('subjects', []);
                })
                ->nullable(),
            Forms\Components\Select::make('school_id')
                ->label('School Name')
                ->relationship('school', 'name', modifyQueryUsing: function (Builder $query, Get $get) {
                    if ($campusId = $get('campus_id')) {
                        $query->where('campus_id', $campusId);
                    }
                    return $query;
                })
                ->live()
                ->afterStateUpdated(function (Set $set) {
                    $set('department_id', null);
                    $set('subjects', []);
                })
                ->searchable()
                ->preload(),
            Forms\Components\Select::make('department_id')
                ->label('Department')
                ->relationship('department', 'name', modifyQueryUsing: function (Builder $query, Get $get) {
                    if ($schoolId = $get('school_id')) {
                        $query->where('faculty_id', $schoolId);
                    }
                    return $query;
                })
                ->live()
                ->afterStateUpdated(function (Set $set) {
                    $set('subjects', []);
                })
                ->searchable()
                ->preload(),
            Forms\Components\Select::make('subjects')
                ->relationship('subjects', 'name', modifyQueryUsing: function (Builder $query, Get $get) {
                    if ($deptId = $get('department_id')) {
                        $query->where('department_id', $deptId);
                    } elseif ($schoolId = $get('school_id')) {
                        $query->where('faculty_id', $schoolId);
                    }
                    return $query;
                })
                ->multiple()
                ->preload()
                ->searchable(['name', 'code'])
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->name}"),
        ];
    }
}
