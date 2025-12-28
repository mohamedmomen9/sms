<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Component;

class TranslatableInput
{
    /**
     * Create a translatable input with tabs for each locale.
     *
     * @param string $baseName The base field name (e.g., 'name' becomes 'name_en', 'name_ar')
     * @param string $componentClass The Filament component class to use (e.g., TextInput::class)
     * @param callable|null $configure Optional callback to configure each field
     * @param array|null $locales Override the default locales
     * @return Tabs
     */
    public static function make(string $baseName, string $componentClass, ?callable $configure = null, ?array $locales = null): Tabs
    {
        $locales = $locales ?: config('localization.locales', ['en', 'ar']);
        $localeLabels = config('localization.locale_labels', [
            'en' => 'English',
            'ar' => 'العربية',
        ]);

        // Get the translated base name for the field label
        $baseNameKey = 'app.' . ucfirst($baseName);
        $translatedBaseName = __($baseNameKey);
        
        // If translation not found, use the base name
        if ($translatedBaseName === $baseNameKey) {
            $translatedBaseName = ucfirst($baseName);
        }

        $tabs = [];

        foreach ($locales as $locale) {
            $tabLabel = $localeLabels[$locale] ?? strtoupper($locale);

            /** @var Component $field */
            $field = $componentClass::make("{$baseName}.{$locale}")
                ->label("{$translatedBaseName} ({$tabLabel})");

            // Allow user to apply extra configuration (required, default, etc.)
            if ($configure) {
                $field = $configure($field, $locale);
            }

            $tabs[] = Tab::make($tabLabel)->schema([$field]);
        }

        return Tabs::make(__('app.' . ucfirst($baseName)))
            ->tabs($tabs)
            ->columnSpanFull()
            ->contained(true);
    }
}
