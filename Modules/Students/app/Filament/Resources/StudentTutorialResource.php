<?php

namespace Modules\Students\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Students\Filament\Resources\StudentTutorialResource\Pages;
use Modules\Students\Filament\Resources\StudentTutorialResource\Tables\StudentTutorialTable;
use Modules\Students\Models\StudentTutorial;

class StudentTutorialResource extends Resource
{
    protected static ?string $model = StudentTutorial::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Academic Management';

    protected static ?string $navigationLabel = 'Tutorial Analytics';

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
        return StudentTutorialTable::table($table);
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
            'index' => Pages\ListStudentTutorials::route('/'),
        ];
    }
}
