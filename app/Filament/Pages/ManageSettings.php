<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $slug = 'settings';
    protected static ?int $navigationSort = 100;

    public static function getNavigationLabel(): string
    {
        return __('app.Settings');
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('app.Site Settings');
    }

    public function getSubheading(): ?string
    {
        return __('app.Manage your site configuration, branding, and preferences');
    }

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => Setting::get('site_name', config('app.name', 'Codeness SMS')),
            'university_name' => Setting::get('university_name', ''),
            'site_logo' => Setting::get('site_logo', ''),
            'locale' => Session::get('locale', config('app.locale', 'en')),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Site Display Section
                Section::make(__('app.Site Display'))
                    ->description(__('app.Configure how your site appears to users'))
                    ->icon('heroicon-o-paint-brush')
                    ->schema([
                        TextInput::make('site_name')
                            ->label(__('app.Site Name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Codeness SMS')
                            ->helperText(__('app.The name displayed in the browser tab and header')),

                        TextInput::make('university_name')
                            ->label(__('app.University Name'))
                            ->maxLength(255)
                            ->placeholder('University of Example')
                            ->helperText(__('app.The full name of your university/institution')),

                        FileUpload::make('site_logo')
                            ->label(__('app.Site Logo'))
                            ->image()
                            ->directory('settings')
                            ->visibility('public')
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('200')
                            ->imageResizeTargetHeight('200')
                            ->helperText(__('app.Upload a logo image (recommended: 200x200px)')),
                    ])
                    ->columns(1)
                    ->collapsible(),

                // Language & Localization Section
                Section::make(__('app.Language & Localization'))
                    ->description(__('app.Configure language and regional settings'))
                    ->icon('heroicon-o-language')
                    ->schema([
                        Select::make('locale')
                            ->label(__('app.Display Language'))
                            ->options(config('localization.locale_labels', ['en' => 'English', 'ar' => 'العربية']))
                            ->required()
                            ->native(false)
                            ->helperText(__('app.Select your preferred language. Arabic enables RTL layout.')),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save site settings
        Setting::set('site_name', $data['site_name'] ?? '', 'string', 'site');
        Setting::set('university_name', $data['university_name'] ?? '', 'string', 'site');
        Setting::set('site_logo', $data['site_logo'] ?? '', 'string', 'site');

        // Save and apply language
        $locale = $data['locale'] ?? config('localization.default_locale', 'en');
        $supportedLocales = config('localization.locales', ['en', 'ar']);
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('localization.default_locale', 'en');
        }
        Session::put('locale', $locale);
        App::setLocale($locale);

        Notification::make()
            ->title(__('app.Settings saved successfully'))
            ->success()
            ->send();

        // Redirect to refresh the page and apply new settings
        $this->redirect(static::getUrl());
    }

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Allow if user is admin or has Super Admin role
        return $user->is_admin || $user->hasRole('Super Admin');
    }
}

