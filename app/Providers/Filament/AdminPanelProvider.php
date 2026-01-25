<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Setting;
use Filament\PanelProvider;
use App\Http\Middleware\SetLocale;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Nwidart\Modules\Facades\Module;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Filament\Support\SidebarColorStyles;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName($this->getBrandName())
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
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([]);

        foreach (Module::allEnabled() as $module) {
            $studlyName = $module->getStudlyName();
            $panel
                ->discoverResources(in: $module->getExtraPath('app/Filament/Resources'), for: "Modules\\{$studlyName}\\Filament\\Resources")
                ->discoverPages(in: $module->getExtraPath('app/Filament/Pages'), for: "Modules\\{$studlyName}\\Filament\\Pages")
                ->discoverWidgets(in: $module->getExtraPath('app/Filament/Widgets'), for: "Modules\\{$studlyName}\\Filament\\Widgets");
        }

        return $panel
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
            ->renderHook('panels::head.end', fn(): string => SidebarColorStyles::styles())
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(fn() => __('app.Academic Structure')),
                NavigationGroup::make()
                    ->label(fn() => __('campus::app.Campus Management')),
                NavigationGroup::make()
                    ->label(fn() => __('students::app.Academic Management')),
                NavigationGroup::make()
                    ->label(fn() => __('app.Course Management')),
                NavigationGroup::make()
                    ->label(fn() => __('services::app.Service Management')),
                NavigationGroup::make()
                    ->label(fn() => __('marketing::app.Marketing')),
                NavigationGroup::make()
                    ->label(fn() => __('engagement::app.Engagement')),
                NavigationGroup::make()
                    ->label(fn() => __('communications::app.Communications')),
                NavigationGroup::make()
                    ->label(fn() => __('disciplinary::app.Student Affairs')),
                NavigationGroup::make()
                    ->label(fn() => __('admissions::app.Admissions')),
                NavigationGroup::make()
                    ->label(fn() => __('users::app.User Management')),
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
