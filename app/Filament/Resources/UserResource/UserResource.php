<?php

namespace App\Filament\Resources\UserResource;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Schemas\UserForm;
use App\Filament\Resources\UserResource\Tables\UserTable;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'users';

    public static function getNavigationLabel(): string
    {
        return __('app.Users');
    }

    public static function getModelLabel(): string
    {
        return __('app.User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.Users');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.User Management');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(UserForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UserTable::columns())
            ->filters(UserTable::filters())
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
                
                // Admins see all users
                if ($user && $user->isAdmin()) {
                    return $query;
                }
                
                // Non-admins can only see users within their scope
                if ($user) {
                    if ($user->isScopedToFaculty()) {
                        // Faculty-scoped users see users in their faculty
                        return $query->where('faculty_id', $user->faculty_id);
                    }
                    
                    // Subject-scoped users only see themselves
                    return $query->where('id', $user->id);
                }
                
                return $query->whereRaw('1 = 0');
            });
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
            if ($user->isScopedToFaculty()) {
                return $query->where('faculty_id', $user->faculty_id);
            }
            
            return $query->where('id', $user->id);
        }
        
        return $query;
    }
}
