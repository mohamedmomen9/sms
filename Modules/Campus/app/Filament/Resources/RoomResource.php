<?php

namespace Modules\Campus\Filament\Resources;

use Modules\Campus\Filament\Resources\RoomResource\Pages;
use Modules\Campus\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    protected static ?string $navigationGroup = 'Campus Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('building_id')
                    ->relationship('building', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('floor_number')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->label('Room Number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('room_code')
                    ->label('Room Code (Unique)')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->label('Room Name (Optional)')
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'classroom' => 'Classroom',
                        'lab' => 'Lab',
                        'auditorium' => 'Auditorium',
                        'office' => 'Office',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('capacity')
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                    ])
                    ->default('active')
                    ->required(),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\CheckboxList::make('facilities')
                    ->relationship('facilities', 'name')
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('building.name')->sortable(),
                Tables\Columns\TextColumn::make('floor_number')->sortable(),
                Tables\Columns\TextColumn::make('number')->label('Room #')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\TextColumn::make('capacity')->numeric(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'maintenance' => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('building')
                    ->relationship('building', 'name'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'classroom' => 'Classroom',
                        'lab' => 'Lab',
                        'auditorium' => 'Auditorium',
                        'office' => 'Office',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRooms::route('/'),
        ];
    }
}
