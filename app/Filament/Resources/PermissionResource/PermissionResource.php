<?php

namespace App\Filament\Resources\PermissionResource;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\Schemas\PermissionForm;
use App\Filament\Resources\PermissionResource\Tables\PermissionTable;
use App\Models\Permission;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $slug = 'permissions';

    public static function getNavigationLabel(): string
    {
        return __('app.Permissions');
    }

    public static function getModelLabel(): string
    {
        return __('app.Permission');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.Permissions');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.User Management');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(PermissionForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(PermissionTable::columns())
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
