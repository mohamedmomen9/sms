<?php

namespace Modules\Communications\Filament\Resources\NotificationResource\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class NotificationForm
{
    public static function schema(): array
    {
        return [
            Section::make(__('communications::app.Notification Content'))
                ->schema([
                    TextInput::make('title')
                        ->label(__('communications::app.Title'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('subtitle')
                        ->label(__('communications::app.Subtitle'))
                        ->rows(2)
                        ->maxLength(500)
                        ->columnSpanFull(),

                    RichEditor::make('body')
                        ->label(__('communications::app.Body'))
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'link',
                            'orderedList',
                            'bulletList',
                        ])
                        ->columnSpanFull(),
                ])
                ->columns(1),

            Section::make(__('communications::app.App Deep Link Data'))
                ->schema([
                    KeyValue::make('extra_data')
                        ->label(__('communications::app.Extra Data'))
                        ->keyLabel(__('communications::app.Key'))
                        ->valueLabel(__('communications::app.Value'))
                        ->helperText(__('communications::app.Add key-value pairs for app navigation'))
                        ->addActionLabel(__('app.Add'))
                        ->reorderable()
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(),
        ];
    }
}
