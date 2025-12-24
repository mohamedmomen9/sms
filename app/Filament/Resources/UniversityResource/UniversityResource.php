<?php

namespace App\Filament\Resources\UniversityResource;

use App\Filament\Resources\UniversityResource\Pages;
use App\Filament\Resources\UniversityResource\Schemas\UniversityForm;
use App\Filament\Resources\UniversityResource\Tables\UniversityTable;
use App\Models\University;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UniversityResource extends Resource
{
    protected static ?string $model = University::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function form(Form $form): Form
    {
        return $form->schema(UniversityForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UniversityTable::columns())
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUniversities::route('/'),
            'create' => Pages\CreateUniversity::route('/create'),
            'edit' => Pages\EditUniversity::route('/{record}/edit'),
        ];
    }
}
