<?php

namespace Modules\System\Filament\Resources\SystemSettingResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class SystemSettingForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->disabled(fn($context) => $context === 'edit'),

                Select::make('type')
                    ->options([
                        'string' => 'String',
                        'boolean' => 'Boolean',
                        'integer' => 'Integer',
                        'json' => 'JSON',
                    ])
                    ->required()
                    ->reactive(),

                Textarea::make('value')
                    ->columnSpanFull(),

                TextInput::make('group')
                    ->default('general')
                    ->required(),
            ]);
    }
}
