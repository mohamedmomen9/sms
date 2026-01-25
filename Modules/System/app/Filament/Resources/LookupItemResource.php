<?php

namespace Modules\System\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\System\Filament\Resources\LookupItemResource\Pages;
use Modules\System\Filament\Resources\LookupItemResource\Schemas\LookupItemForm;
use Modules\System\Filament\Resources\LookupItemResource\Tables\LookupItemTable;
use Modules\System\Models\LookupItem;

class LookupItemResource extends Resource
{
    protected static ?string $model = LookupItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'System';

    public static function form(Form $form): Form
    {
        return LookupItemForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return LookupItemTable::table($table);
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
            'index' => Pages\ListLookupItems::route('/'),
            'create' => Pages\CreateLookupItem::route('/create'),
            'edit' => Pages\EditLookupItem::route('/{record}/edit'),
        ];
    }
}
