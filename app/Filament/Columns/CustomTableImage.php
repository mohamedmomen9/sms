<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\ImageColumn;

class CustomTableImage extends ImageColumn
{
    // Use a custom named static method instead of overriding 'make'
    public static function storage(string $name): static
    {
        return parent::make($name)
            ->getStateUsing(function ($record) use ($name) {
                $imagePath = data_get($record, $name);
                return $imagePath
                    ? asset('storage/' . $imagePath)
                    : null;
            })
            ->circular()
            ->extraImgAttributes([
                'style' => 'background: radial-gradient(black, transparent)'
            ]);
    }
}
