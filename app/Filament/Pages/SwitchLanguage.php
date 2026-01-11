<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SwitchLanguage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationLabel = null;
    protected static ?string $slug = 'switch-language';
    protected static ?int $navigationSort = 101;

    protected static string $view = 'filament.pages.switch-language';

    public static function getNavigationLabel(): string
    {
        return __('app.Switch Language');
    }

    public function mount(): void
    {
        $currentLocale = App::getLocale();
        $newLocale = $currentLocale === 'ar' ? 'en' : 'ar';
        
        Session::put('locale', $newLocale);
        App::setLocale($newLocale);
        
        $this->redirect(request()->header('Referer', '/admin'));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Language switcher is now in Settings page
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.Settings');
    }

    public static function getNavigationBadge(): ?string
    {
        return strtoupper(App::getLocale());
    }
}
