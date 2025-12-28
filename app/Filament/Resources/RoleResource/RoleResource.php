<?php

namespace App\Filament\Resources\RoleResource;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\Schemas\RoleForm;
use App\Filament\Resources\RoleResource\Tables\RoleTable;
use App\Models\Role;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $slug = 'roles';

    public static function getNavigationLabel(): string
    {
        return __('app.Roles');
    }

    public static function getModelLabel(): string
    {
        return __('app.Role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.Roles');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.User Management');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(RoleForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(RoleTable::columns())
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
