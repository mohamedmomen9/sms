<?php

namespace Modules\Admissions\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Admissions\Filament\Resources\ApplicantResource\Pages;
use Modules\Admissions\Filament\Resources\ApplicantResource\Schemas\ApplicantForm;
use Modules\Admissions\Filament\Resources\ApplicantResource\Tables\ApplicantTable;
use Modules\Admissions\Models\Applicant;

class ApplicantResource extends Resource
{
    protected static ?string $model = Applicant::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Admissions';

    public static function form(Form $form): Form
    {
        return ApplicantForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return ApplicantTable::table($table);
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
            'index' => Pages\ListApplicants::route('/'),
            'create' => Pages\CreateApplicant::route('/create'),
            'edit' => Pages\EditApplicant::route('/{record}/edit'),
        ];
    }
}
