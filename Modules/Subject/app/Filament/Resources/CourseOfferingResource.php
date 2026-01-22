<?php

namespace Modules\Subject\Filament\Resources;

use Modules\Subject\Filament\Resources\CourseOfferingResource\Pages;
use Modules\Subject\Filament\Resources\CourseOfferingResource\RelationManagers;
use Modules\Subject\Filament\Resources\CourseOfferingResource\Schemas\CourseOfferingForm;
use Modules\Subject\Filament\Resources\CourseOfferingResource\Tables\CourseOfferingTable;
use Modules\Subject\Models\CourseOffering;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourseOfferingResource extends Resource
{
    protected static ?string $model = CourseOffering::class;

    public static function getGloballySearchableAttributes(): array
    {
        return ['subject.name', 'section_number'];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->subject->name . ' - Section ' . $record->section_number;
    }

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getNavigationGroup(): ?string
    {
        return __('subject::app.Course Management');
    }

    public static function getModelLabel(): string
    {
        return __('subject::app.Course Offering');
    }

    public static function getPluralModelLabel(): string
    {
        return __('subject::app.Course Offerings');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(CourseOfferingForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(CourseOfferingTable::columns())
            ->filters(CourseOfferingTable::filters())
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
            RelationManagers\SchedulesRelationManager::class,
            RelationManagers\EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseOfferings::route('/'),
            'create' => Pages\CreateCourseOffering::route('/create'),
            'edit' => Pages\EditCourseOffering::route('/{record}/edit'),
        ];
    }
}
