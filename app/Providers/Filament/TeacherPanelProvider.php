<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetLocale;
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
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class TeacherPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('teacher')
            ->path('teacher')
            ->login()
            ->authGuard('teacher')
            ->brandName($this->getBrandName())
            ->favicon($this->getFavicon())
            ->colors([
                'primary' => Color::hex('#2563eb'),
                'warning' => Color::hex('#d4a84b'),
                'success' => Color::Emerald,
                'danger' => Color::Rose,
                'info' => Color::Sky,
            ])
            ->font('Inter')
            ->discoverResources(in: app_path('Filament/TeacherPanel/Resources'), for: 'App\\Filament\\TeacherPanel\\Resources')
            ->discoverPages(in: app_path('Filament/TeacherPanel/Pages'), for: 'App\\Filament\\TeacherPanel\\Pages')
            ->pages([
                \App\Filament\TeacherPanel\Pages\TeacherDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TeacherPanel/Widgets'), for: 'App\\Filament\\TeacherPanel\\Widgets')
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
                    ->label(__('My Classes')),
                NavigationGroup::make()
                    ->label(__('My Subjects')),
            ]);
    }

    protected function getBrandName(): string|HtmlString
    {
        try {
            $siteName = Setting::get('site_name', config('app.name', 'Codeness SMS'));
            $siteLogo = Setting::get('site_logo');
            
            if ($siteLogo) {
                $logoUrl = asset('storage/' . $siteLogo);
                return new HtmlString('<span class="flex items-center gap-2"><img src="' . $logoUrl . '" class="h-8" alt="Logo"><span class="text-xl font-semibold">' . e($siteName) . ' - Teacher Portal</span></span>');
            }
            
            return new HtmlString('<span class="flex items-center gap-2"><img src="' . asset('images/logo.png') . '" class="h-8" alt="Logo"><span class="text-xl font-semibold">' . e($siteName) . ' - Teacher Portal</span></span>');
        } catch (\Exception $e) {
            return new HtmlString('<span class="flex items-center gap-2"><img src="' . asset('images/logo.png') . '" class="h-8" alt="Logo"><span class="text-xl font-semibold">Teacher Portal</span></span>');
        }
    }

    protected function getFavicon(): string
    {
        try {
            $siteLogo = Setting::get('site_logo');
            if ($siteLogo) {
                return asset('storage/' . $siteLogo);
            }
        } catch (\Exception $e) {
            // Fallback during migrations
        }
        return asset('images/logo.png');
    }
}
