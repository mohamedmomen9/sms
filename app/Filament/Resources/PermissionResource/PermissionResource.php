<?php

namespace App\Filament\Resources\PermissionResource;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\Schemas\PermissionForm;
use App\Filament\Resources\PermissionResource\Tables\PermissionTable;
use App\Models\Permission;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
