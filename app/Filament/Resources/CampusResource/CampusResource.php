<?php

namespace App\Filament\Resources\CampusResource;

use App\Filament\Resources\CampusResource\Pages;
use App\Filament\Resources\CampusResource\Schemas\CampusForm;
use App\Filament\Resources\CampusResource\Tables\CampusTable;
use App\Models\Campus;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CampusResource extends Resource
{
    protected static ?string $model = Campus::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Academic Structure';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'campuses';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('app.Campuses');
    }

    public static function getModelLabel(): string
    {
        return __('app.Campus');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.Campuses');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.Academic Structure');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(CampusForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(CampusTable::columns())
            ->filters(CampusTable::filters())
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampuses::route('/'),
            'create' => Pages\CreateCampus::route('/create'),
            'edit' => Pages\EditCampus::route('/{record}/edit'),
        ];
    }

    /**
     * Get the Eloquent query for the resource.
     */
    /**
     * Get the Eloquent query for the resource.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
