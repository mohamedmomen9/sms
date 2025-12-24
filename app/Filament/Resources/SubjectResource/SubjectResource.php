<?php

namespace App\Filament\Resources\SubjectResource;

use App\Filament\Resources\SubjectResource\Pages;
use App\Filament\Resources\SubjectResource\Schemas\SubjectForm;
use App\Filament\Resources\SubjectResource\Tables\SubjectTable;
use App\Models\Subject;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form->schema(SubjectForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(SubjectTable::columns())
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
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
