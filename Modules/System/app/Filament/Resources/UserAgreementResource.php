<?php

namespace Modules\System\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\System\Filament\Resources\UserAgreementResource\Pages;
use Modules\System\Filament\Resources\UserAgreementResource\Tables\UserAgreementTable;
use Modules\System\Models\UserAgreement;

class UserAgreementResource extends Resource
{
    protected static ?string $model = UserAgreement::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'System';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return UserAgreementTable::table($table);
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
            'index' => Pages\ListUserAgreements::route('/'),
        ];
    }
}
