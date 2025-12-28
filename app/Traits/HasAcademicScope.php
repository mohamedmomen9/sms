<?php

namespace App\Traits;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasAcademicScope
 * 
 * Provides methods for determining user's academic scope and filtering queries accordingly.
 * A user can be scoped to:
 * - A University (sees all faculties, departments, subjects within)
 * - A Faculty (sees all departments, subjects within that faculty)
 * - A Subject (sees only that subject and its parent relationships)
 */
trait HasAcademicScope
{
    public function isAdmin(): bool
    {
        return $this->hasPermissionTo('scope:global') || $this->is_admin || $this->role === 'admin';
    }

    public function isScopedToFaculty(): bool
    {
        return $this->hasPermissionTo('scope:faculty');
    }

    public function isScopedToSubject(): bool
    {
        return $this->hasPermissionTo('scope:subject');
    }

    public function getScopedFacultyId(): ?int
    {
        if ($this->isAdmin()) {
            return null;
        }

        if ($this->faculty_id) {
            return $this->faculty_id;
        }

        if ($this->subject_id) {
            $subject = $this->subject;
            if ($subject) {
                if ($subject->faculty_id) {
                    return $subject->faculty_id;
                }
                if ($subject->department_id) {
                    return $subject->department?->faculty_id;
                }
            }
        }

        return null;
    }

    public function getAccessibleFacultyIds(): array
    {
        if ($this->isAdmin()) {
            // In single university, admin sees ALL faculties
            return Faculty::pluck('id')->toArray();
        }

        $facultyId = $this->getScopedFacultyId();
        return $facultyId ? [$facultyId] : [];
    }

    public function getAccessibleDepartmentIds(): array
    {
        if ($this->isAdmin()) {
            return Department::pluck('id')->toArray();
        }

        $facultyIds = $this->getAccessibleFacultyIds();
        if (empty($facultyIds)) {
            return [];
        }

        return Department::whereIn('faculty_id', $facultyIds)->pluck('id')->toArray();
    }

    public function scopeFacultyQuery(Builder $query): Builder
    {
        if ($this->isAdmin()) {
            return $query;
        }

        $facultyId = $this->getScopedFacultyId();
        if ($facultyId) {
            return $query->where('id', $facultyId);
        }

        return $query->whereRaw('1 = 0');
    }

    public function scopeDepartmentQuery(Builder $query): Builder
    {
        if ($this->isAdmin()) {
            return $query;
        }

        $facultyIds = $this->getAccessibleFacultyIds();
        if (!empty($facultyIds)) {
            return $query->whereIn('faculty_id', $facultyIds);
        }

        return $query->whereRaw('1 = 0');
    }

    public function scopeSubjectQuery(Builder $query): Builder
    {
        if ($this->isAdmin()) {
            return $query;
        }

        if ($this->isScopedToSubject()) {
            return $query->where('id', $this->subject_id);
        }

        $facultyIds = $this->getAccessibleFacultyIds();
        if (!empty($facultyIds)) {
            return $query->where(function ($q) use ($facultyIds) {
                $q->whereIn('faculty_id', $facultyIds)
                  ->orWhereHas('department', function ($dq) use ($facultyIds) {
                      $dq->whereIn('faculty_id', $facultyIds);
                  });
            });
        }

        return $query->whereRaw('1 = 0');
    }

    public function canAccessFaculty(Faculty $faculty): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($faculty->id, $this->getAccessibleFacultyIds());
    }

    public function canAccessDepartment(Department $department): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($department->id, $this->getAccessibleDepartmentIds());
    }

    public function canAccessSubject(Subject $subject): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isScopedToSubject()) {
            return $this->subject_id === $subject->id;
        }

        $facultyIds = $this->getAccessibleFacultyIds();
        if (empty($facultyIds)) {
            return false;
        }

        if ($subject->faculty_id && in_array($subject->faculty_id, $facultyIds)) {
            return true;
        }

        if ($subject->department_id && $subject->department) {
            return in_array($subject->department->faculty_id, $facultyIds);
        }

        return false;
    }
}
