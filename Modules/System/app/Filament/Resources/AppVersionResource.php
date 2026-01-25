<?php

namespace Modules\System\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\System\Filament\Resources\AppVersionResource\Pages;
use Modules\System\Filament\Resources\AppVersionResource\Schemas\AppVersionForm;
use Modules\System\Filament\Resources\AppVersionResource\Tables\AppVersionTable;
use Modules\System\Models\AppVersion;

class AppVersionResource extends Resource
{
    protected static ?string $model = AppVersion::class;

    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $navigationGroup = 'System';

    public static function form(Form $form): Form
    {
        return AppVersionForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return AppVersionTable::table($table);
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
            'index' => Pages\ListAppVersions::route('/'),
            'create' => Pages\CreateAppVersion::route('/create'),
            'edit' => Pages\EditAppVersion::route('/{record}/edit'),
        ];
    }
}
