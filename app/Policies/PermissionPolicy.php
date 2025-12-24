<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_permission');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('view_permission');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_permission');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('update_permission');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('delete_permission');
    }
}
