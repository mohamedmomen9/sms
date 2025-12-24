<?php

namespace App\Filament\Resources\FacultyResource;

use App\Filament\Resources\FacultyResource\Pages;
use App\Filament\Resources\FacultyResource\Schemas\FacultyForm;
use App\Filament\Resources\FacultyResource\Tables\FacultyTable;
use App\Models\Faculty;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form->schema(FacultyForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(FacultyTable::columns())
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
            'index' => Pages\ListFaculties::route('/'),
            'create' => Pages\CreateFaculty::route('/create'),
            'edit' => Pages\EditFaculty::route('/{record}/edit'),
        ];
    }
}
