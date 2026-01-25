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
        $resources = Filament::getCurrentPanel()->getResources();

        $resourceCounts = collect($resources)
            ->mapWithKeys(function ($resource) {
                try {
                    $label = $resource::getNavigationLabel();
                    $model = $resource::getModel();
                    $count = 0;
                    if (class_exists($model)) {
                        $count = $model::count();
                    }
                    return [$label => $count];
                } catch (\Exception $e) {
                    return [];
                }
            })
            ->toArray();

        return collect($navigation)
            ->filter(fn($group) => $group->getLabel() !== null)
            ->map(function ($group) use ($resourceCounts) {
                return [
                    'label' => $group->getLabel(),
                    'icon' => $group->getIcon(),
                    'items' => collect($group->getItems())->map(function (NavigationItem $item) use ($resourceCounts) {
                        return [
                            'label' => $item->getLabel(),
                            'url' => $item->getUrl(),
                            'icon' => $item->getIcon(),
                            'isActive' => $item->isActive(),
                            'count' => $resourceCounts[$item->getLabel()] ?? null,
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
