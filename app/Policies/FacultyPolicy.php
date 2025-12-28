<?php

namespace App\Policies;

use App\Models\Faculty;
use App\Models\User;

class FacultyPolicy
{
    /**
     * Determine if the user can view any faculties
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_faculty');
    }

    /**
     * Determine if the user can view the faculty
     */
    public function view(User $user, Faculty $faculty): bool
    {
        if (!$user->hasPermissionTo('view_faculty')) {
            return false;
        }

        // Admins can view any faculty
        if ($user->isAdmin()) {
            return true;
        }

        // Standard users can only view faculties within their scope
        return $user->canAccessFaculty($faculty);
    }

    /**
     * Determine if the user can create faculties
     */
    public function create(User $user): bool
    {
        if (!$user->hasPermissionTo('create_faculty')) {
            return false;
        }

        // Only admins can create faculties freely
        // University-scoped users might be able to create within their university
        return $user->isAdmin() || $user->isScopedToUniversity();
    }

    /**
     * Determine if the user can update the faculty
     */
    public function update(User $user, Faculty $faculty): bool
    {
        if (!$user->hasPermissionTo('update_faculty')) {
            return false;
        }

        // Admins can update any faculty
        if ($user->isAdmin()) {
            return true;
        }

        // University-scoped users can update faculties in their university
        if ($user->isScopedToUniversity()) {
            return $faculty->university_id === $user->university_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the faculty
     */
    public function delete(User $user, Faculty $faculty): bool
    {
        if (!$user->hasPermissionTo('delete_faculty')) {
            return false;
        }

        // Admins can delete any faculty
        if ($user->isAdmin()) {
            return true;
        }

        // University-scoped users can delete faculties in their university
        if ($user->isScopedToUniversity()) {
            return $faculty->university_id === $user->university_id;
        }

        return false;
    }
}
