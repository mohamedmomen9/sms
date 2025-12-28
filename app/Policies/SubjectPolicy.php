<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    /**
     * Determine if the user can view any subjects
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_subject');
    }

    /**
     * Determine if the user can view the subject
     */
    public function view(User $user, Subject $subject): bool
    {
        if (!$user->hasPermissionTo('view_subject')) {
            return false;
        }

        // Admins can view any subject
        if ($user->isAdmin()) {
            return true;
        }

        // Standard users can only view subjects within their scope
        return $user->canAccessSubject($subject);
    }

    /**
     * Determine if the user can create subjects
     */
    public function create(User $user): bool
    {
        if (!$user->hasPermissionTo('create_subject')) {
            return false;
        }

        // Subject-scoped users cannot create new subjects
        if ($user->isScopedToSubject()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the user can update the subject
     */
    public function update(User $user, Subject $subject): bool
    {
        if (!$user->hasPermissionTo('update_subject')) {
            return false;
        }

        // Admins can update any subject
        if ($user->isAdmin()) {
            return true;
        }

        // Check if user has access to this subject
        return $user->canAccessSubject($subject);
    }

    /**
     * Determine if the user can delete the subject
     */
    public function delete(User $user, Subject $subject): bool
    {
        if (!$user->hasPermissionTo('delete_subject')) {
            return false;
        }

        // Admins can delete any subject
        if ($user->isAdmin()) {
            return true;
        }

        // Subject-scoped users cannot delete
        if ($user->isScopedToSubject()) {
            return false;
        }

        // Check if user has access to this subject
        return $user->canAccessSubject($subject);
    }
}
