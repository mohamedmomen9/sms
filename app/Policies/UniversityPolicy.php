<?php

namespace App\Policies;

use App\Models\University;
use App\Models\User;

class UniversityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_university');
    }

    public function view(User $user, University $university): bool
    {
        return $user->hasPermissionTo('view_university');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_university');
    }

    public function update(User $user, University $university): bool
    {
        return $user->hasPermissionTo('update_university');
    }

    public function delete(User $user, University $university): bool
    {
        return $user->hasPermissionTo('delete_university');
    }
}
