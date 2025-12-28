<?php

namespace App\Policies;

use App\Models\Campus;
use App\Models\User;

class CampusPolicy
{
    /**
     * Determine if the user can view any campuses
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_campus');
    }

    /**
     * Determine if the user can view the campus
     */
    public function view(User $user, Campus $campus): bool
    {
        if (!$user->hasPermissionTo('view_campus')) {
            return false;
        }

        // Admins can view any campus
        if ($user->isAdmin()) {
            return true;
        }

        // Standard users can only view campuses within their university
        $universityId = $user->getScopedUniversityId();
        return $universityId && $campus->university_id === $universityId;
    }

    /**
     * Determine if the user can create campuses
     */
    public function create(User $user): bool
    {
        if (!$user->hasPermissionTo('create_campus')) {
            return false;
        }

        // Admins and university-scoped users can create campuses
        return $user->isAdmin() || $user->isScopedToUniversity();
    }

    /**
     * Determine if the user can update the campus
     */
    public function update(User $user, Campus $campus): bool
    {
        if (!$user->hasPermissionTo('update_campus')) {
            return false;
        }

        // Admins can update any campus
        if ($user->isAdmin()) {
            return true;
        }

        // University-scoped users can update campuses in their university
        if ($user->isScopedToUniversity()) {
            return $campus->university_id === $user->university_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the campus
     */
    public function delete(User $user, Campus $campus): bool
    {
        if (!$user->hasPermissionTo('delete_campus')) {
            return false;
        }

        // Admins can delete any campus
        if ($user->isAdmin()) {
            return true;
        }

        // University-scoped users can delete campuses in their university
        if ($user->isScopedToUniversity()) {
            return $campus->university_id === $user->university_id;
        }

        return false;
    }
}
