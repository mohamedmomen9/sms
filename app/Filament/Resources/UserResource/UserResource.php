<?php

namespace App\Filament\Resources\UserResource;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Schemas\UserForm;
use App\Filament\Resources\UserResource\Tables\UserTable;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

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
                
                // Admins see all users
                if ($user && $user->isAdmin()) {
                    return $query;
                }
                
                // Non-admins can only see users within their scope
                if ($user) {
                    if ($user->isScopedToUniversity()) {
                        // University-scoped users see users in their university or its faculties
                        return $query->where(function ($q) use ($user) {
                            $q->where('university_id', $user->university_id)
                              ->orWhereHas('faculty', function ($fq) use ($user) {
                                  $fq->where('university_id', $user->university_id);
                              });
                        });
                    }
                    
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
            if ($user->isScopedToUniversity()) {
                return $query->where(function ($q) use ($user) {
                    $q->where('university_id', $user->university_id)
                      ->orWhereHas('faculty', function ($fq) use ($user) {
                          $fq->where('university_id', $user->university_id);
                      });
                });
            }
            
            if ($user->isScopedToFaculty()) {
                return $query->where('faculty_id', $user->faculty_id);
            }
            
            return $query->where('id', $user->id);
        }
        
        return $query;
    }
}
