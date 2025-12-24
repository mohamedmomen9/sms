<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_subject');
    }

    public function view(User $user, Subject $subject): bool
    {
        return $user->hasPermissionTo('view_subject');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_subject');
    }

    public function update(User $user, Subject $subject): bool
    {
        return $user->hasPermissionTo('update_subject');
    }

    public function delete(User $user, Subject $subject): bool
    {
        return $user->hasPermissionTo('delete_subject');
    }
}
