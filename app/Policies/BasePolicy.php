<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class BasePolicy
{
    use HandlesAuthorization;

    /**
     * The model key, e.g. 'university', 'faculty'.
     */
    protected string $key;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo("view_any_{$this->key}");
    }

    public function view(User $user, $model): bool
    {
        if ($user->hasPermissionTo("view_{$this->key}")) {
            return $this->isInScope($user, $model);
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo("create_{$this->key}");
    }

    public function update(User $user, $model): bool
    {
        if ($user->hasPermissionTo("update_{$this->key}")) {
            return $this->isInScope($user, $model);
        }
        return false;
    }

    public function delete(User $user, $model): bool
    {
        if ($user->hasPermissionTo("delete_{$this->key}")) {
            return $this->isInScope($user, $model);
        }
        return false;
    }

    public function restore(User $user, $model): bool
    {
        if ($user->hasPermissionTo("restore_{$this->key}")) {
            return $this->isInScope($user, $model);
        }
        return false;
    }

    public function forceDelete(User $user, $model): bool
    {
        if ($user->hasPermissionTo("force_delete_{$this->key}")) {
            return $this->isInScope($user, $model);
        }
        return false;
    }

    protected function isInScope(User $user, $model): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        $className = class_basename($model);
        
        if ($className === 'University') {
            return $user->canAccessUniversity($model);
        }
        
        if ($className === 'Faculty') {
            return $user->canAccessFaculty($model);
        }

        if ($className === 'Department') {
            return $user->canAccessDepartment($model);
        }

        if ($className === 'Campus') {
            $uniId = $user->getScopedUniversityId();
            return $uniId && $model->university_id === $uniId;
        }
        
        if ($className === 'Subject') {
            return $user->canAccessSubject($model);
        }

        if ($className === 'User') {
             if ($user->id === $model->id) return true;
             
             if ($model->university_id) {
                 if ($user->getScopedUniversityId() === $model->university_id) return true;
             }
             if ($model->faculty_id) {
                 if (in_array($model->faculty_id, $user->getAccessibleFacultyIds())) return true;
             }
             return false;
        }

        return true; 
    }
}
