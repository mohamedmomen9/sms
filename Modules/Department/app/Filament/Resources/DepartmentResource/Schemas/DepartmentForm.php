<?php

namespace Modules\Department\Filament\Resources\DepartmentResource\Schemas;

use App\Filament\Forms\Components\TranslatableInput;
use Modules\Faculty\Models\Faculty;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class DepartmentForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return [
            Section::make(__('department::app.Academic Assignment'))
                ->description(__('department::app.Select the faculty for this department'))
                ->schema([
                    Select::make('faculty_id')
                        ->label(__('department::app.Faculty'))
                        ->relationship('faculty', 'name')
                        ->options(function () use ($user) {
                            if ($user->isAdmin()) {
                                return Faculty::pluck('name', 'id');
                            }
                            
                            if ($user->isScopedToFaculty()) {
                                return Faculty::where('id', $user->faculty_id)->pluck('name', 'id');
                            }
                            
                            return Faculty::pluck('name', 'id');
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->default(function () use ($user) {
                            if ($user->isScopedToFaculty()) {
                                return $user->faculty_id;
                            }
                            return null;
                        }),
                ]),

            Section::make(__('department::app.Department Details'))
                ->schema([
                    TextInput::make('code')
                        ->label(__('department::app.Department Code'))
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TranslatableInput::make('name', TextInput::class, function ($field, $locale) {
                        return $field->required()->maxLength(255);
                    }),

                    Select::make('status')
                        ->label(__('department::app.Status'))
                        ->options([
                            'active' => __('department::app.Active'),
                            'inactive' => __('department::app.Inactive'),
                            'pending' => __('department::app.Pending'),
                        ])
                        ->required()
                        ->default('active'),
                ])
                ->columns(2),
        ];
    }
}
