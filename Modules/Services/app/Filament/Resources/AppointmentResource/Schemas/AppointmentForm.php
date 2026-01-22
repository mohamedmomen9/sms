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
            Section::make(__('services::app.Appointment Details'))->schema([
                Select::make('student_id')
                    ->label(__('services::app.Student'))
                    ->relationship('student', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('department_id')
                    ->label(__('services::app.Department'))
                    ->relationship('department', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($set) => $set('purpose_id', null)),

                Select::make('purpose_id')
                    ->label(__('services::app.Purpose'))
                    ->relationship(
                        'purpose',
                        'name',
                        fn($query, $get) =>
                        $query->where('department_id', $get('department_id'))
                    )
                    ->required()
                    ->disabled(fn($get) => !$get('department_id')),

                DatePicker::make('appointment_date')
                    ->label(__('services::app.Date'))
                    ->required()
                    ->minDate(now()),

                Select::make('slot_id')
                    ->label(__('services::app.Slot'))
                    ->relationship('slot', 'label')
                    ->required(),

                TextInput::make('phone')
                    ->label(__('services::app.Phone'))
                    ->tel()
                    ->required()
                    ->maxLength(20),

                Textarea::make('notes')
                    ->label(__('services::app.Notes'))
                    ->rows(3)
                    ->maxLength(500),

                Select::make('status')
                    ->label(__('services::app.Status'))
                    ->options([
                        'booked' => __('services::app.Booked'),
                        'completed' => __('services::app.Completed'),
                        'cancelled' => __('services::app.Cancelled'),
                        'no_show' => __('services::app.No Show'),
                    ])
                    ->default('booked')
                    ->required(),

                Select::make('language')
                    ->label(__('services::app.Language'))
                    ->options(['ar' => __('services::app.Arabic'), 'en' => __('services::app.English')])
                    ->default('ar'),
            ])->columns(2),
        ];
    }
}
