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
            ->renderHook('panels::head.end', fn (): string => <<<'CSS'
                <style>
                    /* Campus Management */
                    a[href*="/campuses"] .fi-sidebar-item-icon { color: rgb(239 68 68) !important; } /* Red-500 */
                    a[href*="/buildings"] .fi-sidebar-item-icon { color: rgb(249 115 22) !important; } /* Orange-500 */
                    a[href*="/rooms"] .fi-sidebar-item-icon { color: rgb(245 158 11) !important; } /* Amber-500 */
                    a[href*="/facilities"] .fi-sidebar-item-icon { color: rgb(132 204 22) !important; } /* Lime-500 */

                    /* Academic Structure */
                    a[href*="/academic-years"] .fi-sidebar-item-icon { color: rgb(16 185 129) !important; } /* Emerald-500 */
                    a[href*="/terms"] .fi-sidebar-item-icon { color: rgb(20 184 166) !important; } /* Teal-500 */
                    a[href*="/faculties"] .fi-sidebar-item-icon { color: rgb(6 182 212) !important; } /* Cyan-500 */
                    a[href*="/departments"] .fi-sidebar-item-icon { color: rgb(14 165 233) !important; } /* Sky-500 */
                    a[href*="/curricula"] .fi-sidebar-item-icon, a[href*="/curriculums"] .fi-sidebar-item-icon { color: rgb(59 130 246) !important; } /* Blue-500 */

                    /* Course Management */
                    a[href*="/course-offerings"] .fi-sidebar-item-icon { color: rgb(99 102 241) !important; } /* Indigo-500 */
                    a[href*="/subjects"] .fi-sidebar-item-icon { color: rgb(139 92 246) !important; } /* Violet-500 */
                    a[href*="/session-types"] .fi-sidebar-item-icon { color: rgb(168 85 247) !important; } /* Purple-500 */

                    /* User Management */
                    a[href*="/students"] .fi-sidebar-item-icon { color: rgb(217 70 239) !important; } /* Fuchsia-500 */
                    a[href*="/teachers"] .fi-sidebar-item-icon { color: rgb(244 63 94) !important; } /* Rose-500 */
                    a[href*="/users"] .fi-sidebar-item-icon { color: rgb(100 116 139) !important; } /* Slate-500 */
                    a[href*="/roles"] .fi-sidebar-item-icon { color: rgb(113 113 122) !important; } /* Zinc-500 */
                    a[href*="/permissions"] .fi-sidebar-item-icon { color: rgb(115 115 115) !important; } /* Neutral-500 */
                </style>
            CSS)
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
