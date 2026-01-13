<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Collection;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?int $navigationSort = -2;

    public function getTitle(): string
    {
        return __('Dashboard');
    }

    public static function getNavigationLabel(): string
    {
        return __('Dashboard');
    }

    public function getGroupedNavigation(): Collection
    {
        $navigation = Filament::getCurrentPanel()->getNavigation();
        
        return collect($navigation)
            ->filter(fn ($group) => $group->getLabel() !== null)
            ->map(function ($group) {
                return [
                    'label' => $group->getLabel(),
                    'icon' => $group->getIcon(),
                    'items' => collect($group->getItems())->map(function (NavigationItem $item) {
                        return [
                            'label' => $item->getLabel(),
                            'url' => $item->getUrl(),
                            'icon' => $item->getIcon(),
                            'isActive' => $item->isActive(),
                        ];
                    })->values()->all(),
                ];
            })
            ->values();
    }
}
