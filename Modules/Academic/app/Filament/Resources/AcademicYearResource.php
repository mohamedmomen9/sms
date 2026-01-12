<?php

namespace Modules\Academic\Filament\Resources;

use Modules\Academic\Filament\Resources\AcademicYearResource\Pages;
use Modules\Academic\Filament\Resources\AcademicYearResource\RelationManagers;
use Modules\Academic\Models\AcademicYear;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

use Modules\Academic\Filament\Resources\AcademicYearResource\Schemas\AcademicYearForm;
use Modules\Academic\Filament\Resources\AcademicYearResource\Tables\AcademicYearTable;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Academic Structure';

    public static function form(Form $form): Form
    {
        return $form->schema(AcademicYearForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(AcademicYearTable::columns())
            ->filters([
                //
            ])
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
        return [
            RelationManagers\TermsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAcademicYears::route('/'),
        ];
    }
}
