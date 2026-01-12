<?php

namespace Modules\Campus\Filament\Resources\RoomResource\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class RoomForm
{
    public static function schema(): array
    {
        return [
            Select::make('building_id')
                ->relationship('building', 'name')
                ->required()
                ->searchable()
                ->preload(),
            TextInput::make('floor_number')
                ->numeric()
                ->required(),
            TextInput::make('number')
                ->label(__('Room Number'))
                ->required()
                ->maxLength(255),
            TextInput::make('room_code')
                ->label(__('Room Code (Unique)'))
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('name')
                ->label(__('Room Name (Optional)'))
                ->maxLength(255),
            Select::make('type')
                ->options([
                    'classroom' => __('Classroom'),
                    'lab' => __('Lab'),
                    'auditorium' => __('Auditorium'),
                    'office' => __('Office'),
                ])
                ->required(),
            TextInput::make('capacity')
                ->numeric(),
            Select::make('status')
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive'),
                    'maintenance' => __('Maintenance'),
                ])
                ->default('active')
                ->required(),
            Select::make('department_id')
                ->relationship('department', 'name')
                ->searchable()
                ->preload(),
            CheckboxList::make('facilities')
                ->relationship('facilities', 'name')
                ->columns(2),
        ];
    }
}
