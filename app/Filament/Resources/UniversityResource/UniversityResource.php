<?php

namespace App\Filament\Resources\UniversityResource;

use App\Filament\Resources\UniversityResource\Pages;
use App\Filament\Resources\UniversityResource\Schemas\UniversityForm;
use App\Filament\Resources\UniversityResource\Tables\UniversityTable;
use App\Models\University;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UniversityResource extends Resource
{
    protected static ?string $model = University::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Academic Structure';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema(UniversityForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UniversityTable::columns())
            ->filters(UniversityTable::filters())
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                
                if ($user && !$user->isAdmin()) {
                    return $user->scopeUniversityQuery($query);
                }
                
                return $query;
            });
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUniversities::route('/'),
            'create' => Pages\CreateUniversity::route('/create'),
            'edit' => Pages\EditUniversity::route('/{record}/edit'),
        ];
    }

    /**
     * Get the Eloquent query for the resource.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user && !$user->isAdmin()) {
            return $user->scopeUniversityQuery($query);
        }
        
        return $query;
    }
}
