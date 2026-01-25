<?php

namespace Modules\Admissions\Filament\Resources\ApplicantResource\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class ApplicantForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Applicant Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create'),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'reviewed' => 'Reviewed',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),
                    ])
                    ->columns(2),

                Section::make('Application Data')
                    ->schema([
                        KeyValue::make('application_data')
                            ->label('Additional Fields')
                            ->keyLabel('Field')
                            ->valueLabel('Value'),
                    ])
                    ->collapsible(),
            ]);
    }
}
