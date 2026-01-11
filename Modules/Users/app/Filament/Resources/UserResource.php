<?php

namespace Modules\Users\Filament\Resources;

use Modules\Users\Filament\Resources\UserResource\Pages;
use Modules\Users\Filament\Resources\UserResource\Schemas\UserForm;
use Modules\Users\Filament\Resources\UserResource\Tables\UserTable;
use Modules\Users\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'users';

    public static function getNavigationLabel(): string
    {
        return __('users::app.Users');
    }

    public static function getModelLabel(): string
    {
        return __('users::app.User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('users::app.Users');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('users::app.User Management');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(UserForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UserTable::columns())
            ->filters(UserTable::filters())
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
