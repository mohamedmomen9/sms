<?php

namespace Modules\Campus\Filament\Resources;

use Modules\Campus\Filament\Resources\RoomResource\Pages;
use Modules\Campus\Filament\Resources\RoomResource\Schemas\RoomForm;
use Modules\Campus\Filament\Resources\RoomResource\Tables\RoomTable;
use Modules\Campus\Models\Room;
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
        return $form->schema(RoomForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(RoomTable::columns())
            ->filters(RoomTable::filters())
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
