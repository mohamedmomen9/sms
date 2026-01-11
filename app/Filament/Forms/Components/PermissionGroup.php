<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionGroup extends CheckboxList
{
    protected string $view = 'components.filament.forms.components.permission-group';

    protected function setUp(): void
    {
        parent::setUp();

        $this->options(function () {
            return Permission::all()->pluck('name', 'id')->toArray();
        });
    }

    public function getGroupedPermissions(): array
    {
        $permissions = Permission::all();
        $groups = [];

        foreach ($permissions as $permission) {
            // Special handling for Scopes
            if (str_starts_with($permission->name, 'scope:')) {
                $groups['Scopes'][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'label' => Str::title(str_replace('scope:', '', $permission->name)),
                ];
                continue;
            }

            $bits = explode('_', $permission->name);
            $resource = end($bits);

            $action = Str::beforeLast($permission->name, "_{$resource}");
            $action = str_replace('_', ' ', $action);
            $action = ucwords($action);
            
            $groupName = Str::title($resource);

            $groups[$groupName][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'label' => $action,
            ];
        }

        ksort($groups);
        return $groups;
    }
}
