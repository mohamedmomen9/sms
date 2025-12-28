<?php

namespace App\Filament\Resources\SubjectResource;

use App\Filament\Resources\SubjectResource\Pages;
use App\Filament\Resources\SubjectResource\Schemas\SubjectForm;
use App\Filament\Resources\SubjectResource\Tables\SubjectTable;
use App\Models\Subject;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Academic Structure';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema(SubjectForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(SubjectTable::columns())
            ->filters(SubjectTable::filters())
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
                    return $user->scopeSubjectQuery($query);
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
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
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
            return $user->scopeSubjectQuery($query);
        }
        
        return $query;
    }
}
