<?php

namespace App\Policies;

use App\Models\University;
use App\Models\User;

class UniversityPolicy
{
    /**
     * Determine if the user can view any universities
     */
    public function viewAny(User $user): bool
    {
        // Standard users can view universities (filtered by scope)
        return $user->hasPermissionTo('view_any_university');
    }

    /**
     * Determine if the user can view the university
     */
    public function view(User $user, University $university): bool
    {
        if (!$user->hasPermissionTo('view_university')) {
            return false;
        }

        // Admins can view any university
        if ($user->isAdmin()) {
            return true;
        }

        // Standard users can only view their scoped university
        return $user->canAccessUniversity($university);
    }

    /**
     * Determine if the user can create universities
     */
    public function create(User $user): bool
    {
        // Only admins can create universities
        if (!$user->hasPermissionTo('create_university')) {
            return false;
        }

        return $user->isAdmin();
    }

    /**
     * Determine if the user can update the university
     */
    public function update(User $user, University $university): bool
    {
        if (!$user->hasPermissionTo('update_university')) {
            return false;
        }

        // Only admins can update universities
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete the university
     */
    public function delete(User $user, University $university): bool
    {
        if (!$user->hasPermissionTo('delete_university')) {
            return false;
        }

        // Only admins can delete universities
        return $user->isAdmin();
    }
}
