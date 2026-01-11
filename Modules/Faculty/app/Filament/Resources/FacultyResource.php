<?php

namespace Modules\Faculty\Filament\Resources;

use Modules\Faculty\Filament\Resources\FacultyResource\Pages;
use Modules\Faculty\Filament\Resources\FacultyResource\Schemas\FacultyForm;
use Modules\Faculty\Filament\Resources\FacultyResource\Tables\FacultyTable;
use Modules\Faculty\Models\Faculty;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Academic Structure';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'faculties';

    public static function getNavigationLabel(): string
    {
        return __('faculty::app.Faculties');
    }

    public static function getModelLabel(): string
    {
        return __('faculty::app.Faculty');
    }

    public static function getPluralModelLabel(): string
    {
        return __('faculty::app.Faculties');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.Academic Structure');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(FacultyForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(FacultyTable::columns())
            ->filters(FacultyTable::filters())
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                
                if ($user && !$user->isAdmin()) {
                    return $user->scopeFacultyQuery($query);
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
            'index' => Pages\ListFaculties::route('/'),
            'create' => Pages\CreateFaculty::route('/create'),
            'edit' => Pages\EditFaculty::route('/{record}/edit'),
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
            return $user->scopeFacultyQuery($query);
        }
        
        return $query;
    }
}
