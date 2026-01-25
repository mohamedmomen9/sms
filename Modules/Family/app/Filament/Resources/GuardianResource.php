<?php

namespace Modules\Family\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Family\Filament\Resources\GuardianResource\Pages;
use Modules\Family\Filament\Resources\GuardianResource\Schemas\GuardianForm;
use Modules\Family\Filament\Resources\GuardianResource\Tables\GuardianTable;
use Modules\Family\Models\Guardian;

class GuardianResource extends Resource
{
    protected static ?string $model = Guardian::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $modelLabel = 'Parent';

    public static function form(Form $form): Form
    {
        return GuardianForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return GuardianTable::table($table);
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
            'index' => Pages\ListGuardians::route('/'),
            'create' => Pages\CreateGuardian::route('/create'),
            'edit' => Pages\EditGuardian::route('/{record}/edit'),
        ];
    }
}
