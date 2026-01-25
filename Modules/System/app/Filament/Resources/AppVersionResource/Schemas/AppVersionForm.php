<?php

namespace Modules\System\Filament\Resources\AppVersionResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;

class AppVersionForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('platform')
                    ->options([
                        'ios' => 'iOS',
                        'android' => 'Android',
                        'web' => 'Web',
                    ])
                    ->required()
                    ->disabled(),
                TextInput::make('version')
                    ->required()
                    ->maxLength(255),
                TextInput::make('min_version')
                    ->maxLength(255),
                Toggle::make('force_update')
                    ->inline(false)
                    ->required(),
                Textarea::make('release_notes')
                    ->label('Release Notes')
                    ->columnSpanFull(),
            ]);
    }
}
