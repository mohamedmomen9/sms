<?php

namespace App\Filament\Forms\Components;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Component;

class TranslatableInput
{
    public static function make(string $baseName, string $componentClass, ?callable $configure = null, $locales = null): Tabs
    {
        $locales = $locales ?: config('app.locales', ['en', 'ar']);

        $localeLabels = [
            'en' => 'English',
            'ar' => 'العربية',
        ];

        $tabs = [];

        foreach ($locales as $locale) {
            $tabLabel = $localeLabels[$locale] ?? strtoupper($locale);

            /** @var Component $field */
            $field = $componentClass::make("{$baseName}.{$locale}")
                ->label(ucfirst($baseName) . " ({$locale})");

            // Allow user to apply extra configuration (required, default, etc.)
            if ($configure) {
                $field = $configure($field, $locale);
            }

            $tabs[] = Tab::make($tabLabel)->schema([$field]);
        }

        return Tabs::make($baseName)
            ->tabs($tabs)
            ->columnSpanFull()
            ->contained(true);
    }
}
