<?php

namespace Modules\Department\Filament\Resources;

use Modules\Department\Filament\Resources\DepartmentResource\Pages;
use Modules\Department\Filament\Resources\DepartmentResource\Schemas\DepartmentForm;
use Modules\Department\Filament\Resources\DepartmentResource\Tables\DepartmentTable;
use Modules\Department\Models\Department;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Academic Structure';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'departments';

    public static function getNavigationLabel(): string
    {
        return __('department::app.Departments');
    }

    public static function getModelLabel(): string
    {
        return __('department::app.Department');
    }

    public static function getPluralModelLabel(): string
    {
        return __('department::app.Departments');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.Academic Structure');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(DepartmentForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(DepartmentTable::columns())
            ->filters(DepartmentTable::filters())
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
                    return $user->scopeDepartmentQuery($query);
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
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
            return $user->scopeDepartmentQuery($query);
        }
        
        return $query;
    }
}
