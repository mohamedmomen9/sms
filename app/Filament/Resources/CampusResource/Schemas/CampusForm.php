<?php

namespace App\Filament\Resources\CampusResource\Schemas;

use App\Filament\Forms\Components\TranslatableInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class CampusForm
{
    public static function schema(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user?->isAdmin() ?? false;

        return [
            Section::make(__('app.Campus Details'))
                ->schema([
                    TextInput::make('code')
                        ->label(__('app.Campus Code'))
                        ->required()
                        ->maxLength(50)
                        ->helperText(__('app.Unique code within the university')),

                    TranslatableInput::make('name', TextInput::class, function ($field, $locale) {
                        return $field->required()->maxLength(255);
                    }),

                    TextInput::make('location')
                        ->label(__('app.Location'))
                        ->maxLength(255)
                        ->placeholder(__('app.e.g., Main City, North District')),

                    Select::make('status')
                        ->label(__('app.Status'))
                        ->options([
                            'active' => __('app.Active'),
                            'inactive' => __('app.Inactive'),
                        ])
                        ->required()
                        ->default('active'),
                ])
                ->columns(2),

            Section::make(__('app.Contact Information'))
                ->schema([
                    Textarea::make('address')
                        ->label(__('app.Full Address'))
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpanFull(),

                    TextInput::make('phone')
                        ->label(__('app.Phone Number'))
                        ->tel()
                        ->maxLength(50),

                    TextInput::make('email')
                        ->label(__('app.Email Address'))
                        ->email()
                        ->maxLength(255),
                ])
                ->columns(2)
                ->collapsible(),
        ];
    }
}
