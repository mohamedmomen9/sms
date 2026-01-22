<?php

namespace Modules\Campus\Filament\Resources;

use Modules\Campus\Filament\Resources\BuildingResource\Pages;
use Modules\Campus\Filament\Resources\BuildingResource\Schemas\BuildingForm;
use Modules\Campus\Filament\Resources\BuildingResource\Tables\BuildingTable;
use Modules\Campus\Models\Building;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BuildingResource extends Resource
{
    protected static ?string $model = Building::class;

    protected static ?string $navigationGroup = 'Campus Management';

    public static function getNavigationGroup(): ?string
    {
        return __('campus::app.Campus Management');
    }

    public static function getModelLabel(): string
    {
        return __('campus::app.Building');
    }

    public static function getPluralModelLabel(): string
    {
        return __('campus::app.Buildings');
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema(BuildingForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(BuildingTable::columns())
            ->filters([])
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
            'index' => Pages\ManageBuildings::route('/'),
        ];
    }
}
