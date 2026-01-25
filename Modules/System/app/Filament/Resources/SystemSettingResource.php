<?php

namespace Modules\System\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\System\Filament\Resources\SystemSettingResource\Pages;
use Modules\System\Filament\Resources\SystemSettingResource\Schemas\SystemSettingForm;
use Modules\System\Filament\Resources\SystemSettingResource\Tables\SystemSettingTable;
use Modules\System\Models\SystemSetting;

class SystemSettingResource extends Resource
{
    protected static ?string $model = SystemSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'System';

    public static function form(Form $form): Form
    {
        return SystemSettingForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return SystemSettingTable::table($table);
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
            'index' => Pages\ListSystemSettings::route('/'),
            'create' => Pages\CreateSystemSetting::route('/create'),
            'edit' => Pages\EditSystemSetting::route('/{record}/edit'),
        ];
    }
}
