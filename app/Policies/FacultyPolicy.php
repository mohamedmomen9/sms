<?php

namespace App\Policies;

use App\Models\Faculty;
use App\Models\User;

class FacultyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_faculty');
    }

    public function view(User $user, Faculty $faculty): bool
    {
        return $user->hasPermissionTo('view_faculty');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_faculty');
    }

    public function update(User $user, Faculty $faculty): bool
    {
        return $user->hasPermissionTo('update_faculty');
    }

    public function delete(User $user, Faculty $faculty): bool
    {
        return $user->hasPermissionTo('delete_faculty');
    }
}
