<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    /**
     * Determine if the user can view any departments
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_department');
    }

    /**
     * Determine if the user can view the department
     */
    public function view(User $user, Department $department): bool
    {
        if (!$user->hasPermissionTo('view_department')) {
            return false;
        }

        // Admins can view any department
        if ($user->isAdmin()) {
            return true;
        }

        // Standard users can only view departments within their scope
        return $user->canAccessDepartment($department);
    }

    /**
     * Determine if the user can create departments
     */
    public function create(User $user): bool
    {
        if (!$user->hasPermissionTo('create_department')) {
            return false;
        }

        // Admins can create departments
        // University/Faculty-scoped users can create within their scope
        return $user->isAdmin() || $user->isScopedToUniversity() || $user->isScopedToFaculty();
    }

    /**
     * Determine if the user can update the department
     */
    public function update(User $user, Department $department): bool
    {
        if (!$user->hasPermissionTo('update_department')) {
            return false;
        }

        // Admins can update any department
        if ($user->isAdmin()) {
            return true;
        }

        // Check if user has access to this department
        return $user->canAccessDepartment($department);
    }

    /**
     * Determine if the user can delete the department
     */
    public function delete(User $user, Department $department): bool
    {
        if (!$user->hasPermissionTo('delete_department')) {
            return false;
        }

        // Admins can delete any department
        if ($user->isAdmin()) {
            return true;
        }

        // Check if user has access to this department
        return $user->canAccessDepartment($department);
    }
}
