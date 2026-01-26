<?php

namespace Modules\Marketing\Providers;

use Filament\Panel;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MarketingServiceProvider extends ServiceProvider
{
    /**
     * The module name.
     */
    protected string $moduleName = 'Marketing';

    /**
     * The module name in lowercase.
     */
    protected string $moduleNameLower = 'marketing';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->registerRoutes();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Routes not yet configured
    }

    /**
     * Register commands in the format of Command::class.
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Register this module's Filament resources into a panel.
     * 
     * Usage in your panel provider:
     * \Modules\Marketing\Providers\MarketingServiceProvider::registerFilament($panel);
     */
    public static function registerFilament(Panel $panel): void
    {
        $panel->discoverResources(
            in: module_path('Marketing', 'app/Filament/Resources'),
            for: 'Modules\\Marketing\\Filament\\Resources'
        );
    }

    /**
     * Register the routes for the module.
     */
    protected function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(module_path($this->moduleName, 'routes/api.php'));
    }

    /**
     * Get the Filament resource namespace for this module.
     */
    public static function getFilamentResourceNamespace(): string
    {
        return 'Modules\\Marketing\\Filament\\Resources';
    }

    /**
     * Get the Filament resource path for this module.
     */
    public static function getFilamentResourcePath(): string
    {
        return module_path('Marketing', 'app/Filament/Resources');
    }
}
