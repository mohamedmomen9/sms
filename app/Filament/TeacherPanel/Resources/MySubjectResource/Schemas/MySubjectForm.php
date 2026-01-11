<?php

namespace App\Filament\TeacherPanel\Resources\MySubjectResource\Schemas;

use Filament\Forms\Components\TextInput;

class MySubjectForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('code')
                ->label(__('Code'))
                ->disabled(),
            TextInput::make('name')
                ->label(__('Name'))
                ->disabled(),
            TextInput::make('faculty.name')
                ->label(__('Faculty'))
                ->disabled(),
        ];
    }
}
