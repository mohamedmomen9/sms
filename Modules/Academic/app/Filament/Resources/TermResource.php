<?php

namespace Modules\Academic\Filament\Resources;

use Modules\Academic\Filament\Resources\TermResource\Pages;
use Modules\Academic\Filament\Resources\TermResource\Schemas\TermForm;
use Modules\Academic\Filament\Resources\TermResource\Tables\TermTable;
use Modules\Academic\Models\Term;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class TermResource extends Resource
{
    protected static ?string $model = Term::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Academic Structure';

    protected static ?string $navigationParentItem = 'Academic Years';

    public static function form(Form $form): Form
    {
        return $form->schema(TermForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(TermTable::columns())
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTerms::route('/'),
            'create' => Pages\CreateTerm::route('/create'),
            'edit' => Pages\EditTerm::route('/{record}/edit'),
        ];
    }
}
