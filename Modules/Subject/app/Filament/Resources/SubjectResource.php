<?php

namespace Modules\Subject\Filament\Resources;

use Modules\Subject\Filament\Resources\SubjectResource\Pages;
use Modules\Subject\Filament\Resources\SubjectResource\Schemas\SubjectForm;
use Modules\Subject\Filament\Resources\SubjectResource\Tables\SubjectTable;
use Modules\Subject\Models\Subject;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Academic Structure';

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'subjects';

    public static function getNavigationLabel(): string
    {
        return __('app.Subjects');
    }

    public static function getModelLabel(): string
    {
        return __('app.Subject');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.Subjects');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.Academic Structure');
    }

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
