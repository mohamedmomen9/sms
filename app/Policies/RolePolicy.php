<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_role');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('view_role');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_role');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('update_role');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('delete_role');
    }
}
