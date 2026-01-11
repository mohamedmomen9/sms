<?php

namespace Modules\Teachers\Filament\Resources\TeacherResource\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Hash;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;

class TeacherForm
{
    public static function schema(): array
    {
        return [
            Section::make(__('Personal Information'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label(__('Email'))
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    TextInput::make('password')
                        ->label(__('Password'))
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create'),
                    TextInput::make('phone')
                        ->label(__('Phone'))
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('qualification')
                        ->label(__('Qualification'))
                        ->maxLength(255),
                ])
                ->columns(2),
                
            Section::make(__('Campus Assignment'))
                ->schema([
                    Select::make('campus_id')
                        ->label(__('Campus'))
                        ->relationship('campus', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                ]),
                
            Section::make(__('Faculty & Subject Assignments'))
                ->description(__('Select the faculties this teacher belongs to and the subjects they teach'))
                ->schema([
                    Select::make('faculties')
                        ->label(__('Faculties'))
                        ->multiple()
                        ->relationship('faculties', 'name')
                        ->maxItems(1)
                        ->options(function () {
                            return Faculty::all()->mapWithKeys(function ($faculty) {
                                $name = is_array($faculty->name) 
                                    ? ($faculty->name[app()->getLocale()] ?? $faculty->name['en'] ?? '') 
                                    : $faculty->name;
                                return [$faculty->id => $name];
                            });
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (Set $set) => $set('subjects', [])),
                        
                ])
                ->columns(1),
        ];
    }
}
