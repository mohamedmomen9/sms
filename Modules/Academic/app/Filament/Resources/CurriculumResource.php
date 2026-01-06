<?php

namespace Modules\Academic\Filament\Resources;

use Modules\Academic\Filament\Resources\CurriculumResource\Pages;
use Modules\Academic\Filament\Resources\CurriculumResource\Schemas\CurriculumForm;
use Modules\Academic\Filament\Resources\CurriculumResource\Tables\CurriculumTable;
use Modules\Academic\Models\Curriculum;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CurriculumResource extends Resource
{
    protected static ?string $model = Curriculum::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Academic Structure';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'curricula';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('app.Curricula');
    }

    public static function getModelLabel(): string
    {
        return __('app.Curriculum');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.Curricula');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.Academic Structure');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(CurriculumForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(CurriculumTable::columns())
            ->filters(CurriculumTable::filters())
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
            'index' => Pages\ListCurricula::route('/'),
            'create' => Pages\CreateCurriculum::route('/create'),
            'edit' => Pages\EditCurriculum::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
