<?php

namespace Modules\Family\Filament\Resources\GuardianResource\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Modules\Students\Models\Student;

class GuardianForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Parent Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Student Link')
                    ->schema([
                        Select::make('student_id')
                            ->label('Student')
                            ->relationship('student', 'name')
                            ->getOptionLabelFromRecordUsing(fn(Student $record) => $record->name)
                            ->searchable(['name', 'email'])
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }
}
