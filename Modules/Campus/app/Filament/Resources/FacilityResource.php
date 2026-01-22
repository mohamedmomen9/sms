<?php

namespace Modules\Campus\Filament\Resources;

use Modules\Campus\Filament\Resources\FacilityResource\Pages;
use Modules\Campus\Filament\Resources\FacilityResource\Schemas\FacilityForm;
use Modules\Campus\Filament\Resources\FacilityResource\Tables\FacilityTable;
use Modules\Campus\Models\Facility;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationGroup = 'Campus Management';

    public static function getNavigationGroup(): ?string
    {
        return __('campus::app.Campus Management');
    }

    public static function getModelLabel(): string
    {
        return __('campus::app.Facility');
    }

    public static function getPluralModelLabel(): string
    {
        return __('campus::app.Facilities');
    }

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema(FacilityForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(FacilityTable::columns())
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
            'index' => Pages\ManageFacilities::route('/'),
        ];
    }
}
