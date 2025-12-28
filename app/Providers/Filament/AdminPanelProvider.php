<?php

namespace App\Providers\Filament;

use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\HtmlString;
use App\Http\Middleware\SetLocale;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName($this->getBrandName())
            ->brandLogo($this->getBrandLogo())
            ->favicon($this->getFavicon())
            ->colors([
                'primary' => Color::hex('#0077be'),
                'warning' => Color::hex('#d4a84b'),
                'success' => Color::Emerald,
                'danger' => Color::Rose,
                'info' => Color::Sky,
            ])
            ->font('Inter')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(__('Academic Structure')),
                NavigationGroup::make()
                    ->label(__('User Management')),
            ]);
    }

    protected function getBrandName(): string|HtmlString
    {
        try {
            $siteName = Setting::get('site_name', config('app.name', 'Codeness SMS'));
            $siteLogo = Setting::get('site_logo');
            
            if ($siteLogo) {
                $logoUrl = asset('storage/' . $siteLogo);
                return new HtmlString('<span class="flex items-center gap-2"><img src="' . $logoUrl . '" class="h-8" alt="Logo"><span class="text-xl font-semibold">' . e($siteName) . '</span></span>');
            }
            
            // Fallback: use default logo
            return new HtmlString('<span class="flex items-center gap-2"><img src="' . asset('images/logo.png') . '" class="h-8" alt="Logo"><span class="text-xl font-semibold">' . e($siteName) . '</span></span>');
        } catch (\Exception $e) {
            // During migrations, settings table might not exist
            return new HtmlString('<span class="flex items-center gap-2"><img src="' . asset('images/logo.png') . '" class="h-8" alt="Logo"><span class="text-xl font-semibold">Codeness SMS</span></span>');
        }
    }

    protected function getBrandLogo(): ?string
    {
        try {
            $siteLogo = Setting::get('site_logo');
            if ($siteLogo) {
                return asset('storage/' . $siteLogo);
            }
        } catch (\Exception $e) {
            // Ignore during migrations
        }
        return null;
    }

    protected function getFavicon(): string
    {
        try {
            $siteLogo = Setting::get('site_logo');
            if ($siteLogo) {
                return asset('storage/' . $siteLogo);
            }
        } catch (\Exception $e) {
            // Ignore during migrations
        }
        return asset('images/logo.png');
    }
}
