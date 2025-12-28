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

        // Automatically load options if not set, organized for the view
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

            // Standard Logic: "action_resource" -> Group: "Resource", Action: "Action"
            // Example: "view_any_university" -> Group: "University", Action: "View Any"
            
            $bits = explode('_', $permission->name);
            $resource = end($bits);
            
            // Handle cases with multiple underscores if needed, but for now assuming standard naming
            // If resource is "university", removing it from name gives "view_any"
            
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
