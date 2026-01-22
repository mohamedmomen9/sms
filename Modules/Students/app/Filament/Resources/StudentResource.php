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

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Academic Management';

    public static function getNavigationGroup(): ?string
    {
        return __('students::app.Academic Management');
    }

    public static function getModelLabel(): string
    {
        return __('students::app.Student');
    }

    public static function getPluralModelLabel(): string
    {
        return __('students::app.Students');
    }

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
