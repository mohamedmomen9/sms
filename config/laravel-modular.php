<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Modular Configuration
    |--------------------------------------------------------------------------
    |
    | This package enhances nwidart/laravel-modules with Filament v4 support,
    | pattern inference, and customizable module generation.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Auto-detect Patterns
    |--------------------------------------------------------------------------
    |
    | When enabled, the package will analyze your existing codebase to detect
    | patterns and apply them to new modules. Disable to use explicit config.
    |
    */
    'auto_detect_patterns' => true,

    /*
    |--------------------------------------------------------------------------
    | Filament Integration
    |--------------------------------------------------------------------------
    |
    | Configure how Filament resources are generated within modules.
    |
    */
    'filament' => [
        // Enable Filament resource generation
        'enabled' => true,

        // Resource layout: 'nested' (ResourceName/ResourceName.php) or 'flat'
        'layout' => 'nested',

        // Generate separate form schema classes
        'generate_form_schemas' => true,

        // Generate separate table classes
        'generate_table_classes' => true,

        // Subdirectories to create within each resource
        'subdirectories' => ['Pages', 'Schemas', 'Tables'],

        // Default navigation icon for new resources
        'default_icon' => 'heroicon-o-rectangle-stack',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Structure
    |--------------------------------------------------------------------------
    |
    | Define which directories should be generated for new modules.
    | Set to true to generate, false to skip.
    |
    */
    'structure' => [
        // Core directories
        'app/Models' => true,
        'app/Policies' => true,
        'app/Providers' => true,
        'app/Http/Controllers' => true,
        'app/Http/Requests' => false,
        'app/Http/Middleware' => false,

        // Filament directories
        'app/Filament/Resources' => true,
        'app/Filament/Pages' => false,
        'app/Filament/Widgets' => false,

        // Domain directories
        'app/Services' => false,
        'app/Repositories' => false,
        'app/Actions' => false,
        'app/Events' => false,
        'app/Listeners' => false,
        'app/Jobs' => false,

        // Database
        'database/migrations' => true,
        'database/seeders' => true,
        'database/factories' => true,

        // Resources
        'resources/views' => false,
        'resources/assets' => false,

        // Config and routes
        'config' => true,
        'routes' => true,
        'lang' => true,

        // Tests
        'tests/Feature' => false,
        'tests/Unit' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Directories
    |--------------------------------------------------------------------------
    |
    | Add your own custom directories to be generated in each module.
    | These will be appended to the structure above.
    |
    */
    'custom_directories' => [
        // 'app/Domain/ValueObjects' => true,
        // 'app/Domain/Enums' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Locales to generate translation files for.
    | Set to null to auto-detect from config/localization.php or lang directory.
    |
    */
    'locales' => null, // Auto-detect, or ['en', 'ar', 'es']

    /*
    |--------------------------------------------------------------------------
    | Model Generation
    |--------------------------------------------------------------------------
    |
    | Options for model generation within modules.
    |
    */
    'models' => [
        // Use Spatie Translatable trait
        'use_translatable' => true,

        // Translatable fields for new models
        'translatable_fields' => ['name', 'description'],

        // Use soft deletes
        'use_soft_deletes' => false,

        // Use UUIDs instead of auto-incrementing IDs
        'use_uuids' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Policy Generation
    |--------------------------------------------------------------------------
    |
    | Options for policy generation within modules.
    |
    */
    'policies' => [
        // Extend a base policy class (null for standalone)
        'base_class' => null, // e.g., 'App\\Policies\\BasePolicy'

        // Auto-register policies
        'auto_register' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Provider Template
    |--------------------------------------------------------------------------
    |
    | Customize the service provider template.
    |
    */
    'service_provider' => [
        // Include Filament registration helper method
        'include_filament_helper' => true,

        // Auto-register translations
        'register_translations' => true,

        // Auto-register config
        'register_config' => true,

        // Load routes
        'load_routes' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Stubs Path
    |--------------------------------------------------------------------------
    |
    | Path to custom stubs. Set to null to use package defaults.
    | You can publish and customize stubs with:
    | php artisan vendor:publish --tag=laravel-modular-stubs
    |
    */
    'stubs_path' => null, // e.g., base_path('stubs/laravel-modular')

    /*
    |--------------------------------------------------------------------------
    | Composer Integration
    |--------------------------------------------------------------------------
    |
    | Configure module composer.json generation.
    |
    */
    'composer' => [
        'vendor' => env('MODULE_VENDOR', 'app'),
        'author' => [
            'name' => env('MODULE_AUTHOR_NAME', 'Developer'),
            'email' => env('MODULE_AUTHOR_EMAIL', 'dev@example.com'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rename Safety
    |--------------------------------------------------------------------------
    |
    | Safety options for the module:rename command.
    |
    */
    'rename' => [
        // Create backup before renaming
        'create_backup' => true,

        // Backup directory
        'backup_path' => storage_path('module-backups'),

        // Scan app directory for references
        'scan_app_references' => true,

        // Scan config directory for references
        'scan_config_references' => true,

        // Update composer autoload
        'update_composer' => true,
    ],
];
