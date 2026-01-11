<?php

namespace Modules\Students\Filament\Resources;

use Modules\Students\Filament\Resources\StudentResource\Pages;
use Modules\Students\Filament\Resources\StudentResource\RelationManagers;
use Modules\Students\Filament\Resources\StudentResource\Schemas\StudentForm;
use Modules\Students\Filament\Resources\StudentResource\Tables\StudentTable;
use Modules\Students\Models\Student;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Academic Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(StudentForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(StudentTable::columns())
            ->filters(StudentTable::filters())
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
