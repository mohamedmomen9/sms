<?php

namespace Modules\Communications\Filament\Resources;

use Modules\Communications\Filament\Resources\AnnouncementResource\Pages;
use Modules\Communications\Filament\Resources\AnnouncementResource\Schemas\AnnouncementForm;
use Modules\Communications\Filament\Resources\AnnouncementResource\Tables\AnnouncementTable;
use Modules\Communications\Models\Announcement;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Communications';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'announcements';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationLabel(): string
    {
        return __('communications::app.Announcements');
    }

    public static function getModelLabel(): string
    {
        return __('communications::app.Announcement');
    }

    public static function getPluralModelLabel(): string
    {
        return __('communications::app.Announcements');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('communications::app.Communications');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(AnnouncementForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(AnnouncementTable::columns())
            ->filters(AnnouncementTable::filters())
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('campus');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'details'];
    }
}
