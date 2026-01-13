<?php

namespace Modules\Subject\Filament\Resources\CourseOfferingResource\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Validation\Rule;
use Modules\Teachers\Models\Teacher;

class CourseOfferingForm
{
    public static function schema(): array
    {
        return [
            Section::make(__('Course Details'))
                ->schema([
                    Select::make('term_id')
                        ->relationship('term', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('subject_id')
                        ->relationship('subject', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('section_number')
                        ->required()
                        ->default('01')
                        ->maxLength(255)
                        ->rule(function (Get $get) {
                            return Rule::unique('course_offerings', 'section_number')
                                ->where('term_id', $get('term_id'))
                                ->where('subject_id', $get('subject_id'))
                                ->ignore($get('id'));
                        }),
                    TextInput::make('capacity')
                        ->numeric()
                        ->default(30)
                        ->required(),
                    Select::make('room_id')
                        ->relationship('room', 'room_code')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->label_name)
                        ->searchable()
                        ->preload(),
                ])
                ->columns(2),

            Section::make(__('Instructors'))
                ->description(__('Assign one or more instructors to this course offering'))
                ->schema([
                    Select::make('teachers')
                        ->label(__('Assigned Instructors'))
                        ->relationship('teachers', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('name')->required(),
                            TextInput::make('email')->email()->required(),
                        ])
                        ->helperText(__('Select all instructors teaching this course section')),
                    Select::make('primary_instructor_id')
                        ->label(__('Primary Instructor'))
                        ->options(fn (Get $get) => Teacher::whereIn('id', $get('teachers') ?? [])->pluck('name', 'id'))
                        ->helperText(__('The main instructor responsible for this section'))
                        ->reactive()
                        ->visible(fn (Get $get) => count($get('teachers') ?? []) > 0),
                ])
                ->columns(1),
        ];
    }
}

