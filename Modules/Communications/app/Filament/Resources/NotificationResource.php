<?php

namespace Modules\Communications\Filament\Resources;

use Modules\Communications\Filament\Resources\NotificationResource\Pages;
use Modules\Communications\Filament\Resources\NotificationResource\Schemas\NotificationForm;
use Modules\Communications\Filament\Resources\NotificationResource\Tables\NotificationTable;
use Modules\Communications\Models\Notification;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Communications';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'notifications';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationLabel(): string
    {
        return __('communications::app.Notifications');
    }

    public static function getModelLabel(): string
    {
        return __('communications::app.Notification');
    }

    public static function getPluralModelLabel(): string
    {
        return __('communications::app.Notifications');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('communications::app.Communications');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(NotificationForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(NotificationTable::columns())
            ->filters(NotificationTable::filters())
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('logs');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'body'];
    }
}
