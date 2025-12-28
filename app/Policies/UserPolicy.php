<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_user');
    }

    /**
     * Determine if the user can view the target user
     */
    public function view(User $user, User $targetUser): bool
    {
        if (!$user->hasPermissionTo('view_user')) {
            return false;
        }

        // Admins can view any user
        if ($user->isAdmin()) {
            return true;
        }

        // Users can view themselves
        if ($user->id === $targetUser->id) {
            return true;
        }

        // University-scoped users can view users in their university or its faculties
        if ($user->isScopedToUniversity()) {
            if ($targetUser->university_id === $user->university_id) {
                return true;
            }
            if ($targetUser->faculty && $targetUser->faculty->university_id === $user->university_id) {
                return true;
            }
        }

        // Faculty-scoped users can view users in their faculty
        if ($user->isScopedToFaculty()) {
            if ($targetUser->faculty_id === $user->faculty_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user can create users
     */
    public function create(User $user): bool
    {
        if (!$user->hasPermissionTo('create_user')) {
            return false;
        }

        // Only admins and university-scoped users can create users
        return $user->isAdmin() || $user->isScopedToUniversity();
    }

    /**
     * Determine if the user can update the target user
     */
    public function update(User $user, User $targetUser): bool
    {
        if (!$user->hasPermissionTo('update_user')) {
            return false;
        }

        // Admins can update any user
        if ($user->isAdmin()) {
            return true;
        }

        // Users can update themselves (limited fields typically)
        if ($user->id === $targetUser->id) {
            return true;
        }

        // Cannot promote users to admin
        if ($targetUser->is_admin) {
            return false;
        }

        // University-scoped users can update users in their scope
        if ($user->isScopedToUniversity()) {
            if ($targetUser->university_id === $user->university_id) {
                return true;
            }
            if ($targetUser->faculty && $targetUser->faculty->university_id === $user->university_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user can delete the target user
     */
    public function delete(User $user, User $targetUser): bool
    {
        if (!$user->hasPermissionTo('delete_user')) {
            return false;
        }

        // Cannot delete yourself
        if ($user->id === $targetUser->id) {
            return false;
        }

        // Only admins can delete admins
        if ($targetUser->is_admin) {
            return $user->isAdmin();
        }

        // Admins can delete any non-admin user
        if ($user->isAdmin()) {
            return true;
        }

        // University-scoped users can delete users in their scope
        if ($user->isScopedToUniversity()) {
            if ($targetUser->university_id === $user->university_id) {
                return true;
            }
            if ($targetUser->faculty && $targetUser->faculty->university_id === $user->university_id) {
                return true;
            }
        }

        return false;
    }
}
