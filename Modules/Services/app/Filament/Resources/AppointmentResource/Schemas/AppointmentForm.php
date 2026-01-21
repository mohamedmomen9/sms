<?php

namespace Modules\Services\Filament\Resources\AppointmentResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class AppointmentForm
{
    public static function schema(): array
    {
        return [
            Section::make('Appointment Details')->schema([
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($set) => $set('purpose_id', null)),

                Select::make('purpose_id')
                    ->relationship(
                        'purpose',
                        'name',
                        fn($query, $get) =>
                        $query->where('department_id', $get('department_id'))
                    )
                    ->required()
                    ->disabled(fn($get) => !$get('department_id')),

                DatePicker::make('appointment_date')
                    ->required()
                    ->minDate(now()),

                Select::make('slot_id')
                    ->relationship('slot', 'label')
                    ->required(),

                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(20),

                Textarea::make('notes')
                    ->rows(3)
                    ->maxLength(500),

                Select::make('status')
                    ->options([
                        'booked' => 'Booked',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'no_show' => 'No Show',
                    ])
                    ->default('booked')
                    ->required(),

                Select::make('language')
                    ->options(['ar' => 'Arabic', 'en' => 'English'])
                    ->default('ar'),
            ])->columns(2),
        ];
    }
}
