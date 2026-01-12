<?php

namespace Modules\Subject\Filament\Resources\CourseOfferingResource\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Validation\Rule;

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
                    Select::make('teacher_id')
                        ->relationship('teacher', 'name')
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
        ];
    }
}
