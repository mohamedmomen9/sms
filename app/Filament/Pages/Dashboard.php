<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Collection;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.pages.dashboard';

    public function getGroupedNavigation(): Collection
    {
        $navigation = Filament::getCurrentPanel()->getNavigation();

        return collect($navigation)
            ->filter(fn($group) => $group->getLabel() !== null)
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
            ->sortBy(function ($group) {
                $isUserManagement = ($group['label'] === __('users::app.User Management') || $group['label'] === 'User Management') ? 1 : 0;
                return [$isUserManagement, -count($group['items'])];
            })
            ->values();
    }
}
