<?php

namespace App\Traits;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\University;
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
    /**
     * Check if the user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin || $this->role === 'admin';
    }

    /**
     * Check if user is scoped to a University (not a Faculty or Subject)
     */
    public function isScopedToUniversity(): bool
    {
        return !$this->isAdmin() 
            && $this->university_id !== null 
            && $this->faculty_id === null 
            && $this->subject_id === null;
    }

    /**
     * Check if user is scoped to a Faculty
     */
    public function isScopedToFaculty(): bool
    {
        return !$this->isAdmin() 
            && $this->faculty_id !== null 
            && $this->subject_id === null;
    }

    /**
     * Check if user is scoped to a Subject
     */
    public function isScopedToSubject(): bool
    {
        return !$this->isAdmin() && $this->subject_id !== null;
    }

    /**
     * Get the user's assigned university ID
     * For Faculty-scoped users, returns the faculty's university
     * For Subject-scoped users, returns the subject's faculty's university
     */
    public function getScopedUniversityId(): ?int
    {
        if ($this->isAdmin()) {
            return null;
        }

        if ($this->university_id) {
            return $this->university_id;
        }

        if ($this->faculty_id) {
            return $this->faculty?->university_id;
        }

        if ($this->subject_id) {
            $subject = $this->subject;
            if ($subject) {
                if ($subject->faculty_id) {
                    return $subject->faculty?->university_id;
                }
                if ($subject->department_id) {
                    return $subject->department?->faculty?->university_id;
                }
            }
        }

        return null;
    }

    /**
     * Get the user's assigned faculty ID
     * For Subject-scoped users, returns the subject's faculty
     */
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

    /**
     * Get all faculty IDs the user has access to
     */
    public function getAccessibleFacultyIds(): array
    {
        if ($this->isAdmin()) {
            return [];
        }

        // University-scoped: all faculties in that university
        if ($this->isScopedToUniversity()) {
            return Faculty::where('university_id', $this->university_id)->pluck('id')->toArray();
        }

        // Faculty-scoped or Subject-scoped: only their faculty
        $facultyId = $this->getScopedFacultyId();
        return $facultyId ? [$facultyId] : [];
    }

    /**
     * Get all department IDs the user has access to
     */
    public function getAccessibleDepartmentIds(): array
    {
        if ($this->isAdmin()) {
            return [];
        }

        $facultyIds = $this->getAccessibleFacultyIds();
        if (empty($facultyIds)) {
            return [];
        }

        return Department::whereIn('faculty_id', $facultyIds)->pluck('id')->toArray();
    }

    /**
     * Scope a university query based on user access
     */
    public function scopeUniversityQuery(Builder $query): Builder
    {
        if ($this->isAdmin()) {
            return $query;
        }

        $universityId = $this->getScopedUniversityId();
        if ($universityId) {
            return $query->where('id', $universityId);
        }

        // No access - return empty
        return $query->whereRaw('1 = 0');
    }

    /**
     * Scope a faculty query based on user access
     */
    public function scopeFacultyQuery(Builder $query): Builder
    {
        if ($this->isAdmin()) {
            return $query;
        }

        if ($this->isScopedToUniversity()) {
            return $query->where('university_id', $this->university_id);
        }

        $facultyId = $this->getScopedFacultyId();
        if ($facultyId) {
            return $query->where('id', $facultyId);
        }

        return $query->whereRaw('1 = 0');
    }

    /**
     * Scope a department query based on user access
     */
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

    /**
     * Scope a subject query based on user access
     */
    public function scopeSubjectQuery(Builder $query): Builder
    {
        if ($this->isAdmin()) {
            return $query;
        }

        // Subject-scoped user: only their subject
        if ($this->isScopedToSubject()) {
            return $query->where('id', $this->subject_id);
        }

        $facultyIds = $this->getAccessibleFacultyIds();
        if (!empty($facultyIds)) {
            // Subjects belonging to faculties directly OR through departments
            return $query->where(function ($q) use ($facultyIds) {
                $q->whereIn('faculty_id', $facultyIds)
                  ->orWhereHas('department', function ($dq) use ($facultyIds) {
                      $dq->whereIn('faculty_id', $facultyIds);
                  });
            });
        }

        return $query->whereRaw('1 = 0');
    }

    /**
     * Check if user can access a specific university
     */
    public function canAccessUniversity(University $university): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->getScopedUniversityId() === $university->id;
    }

    /**
     * Check if user can access a specific faculty
     */
    public function canAccessFaculty(Faculty $faculty): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($faculty->id, $this->getAccessibleFacultyIds());
    }

    /**
     * Check if user can access a specific department
     */
    public function canAccessDepartment(Department $department): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($department->id, $this->getAccessibleDepartmentIds());
    }

    /**
     * Check if user can access a specific subject
     */
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

        // Check if subject belongs to accessible faculty
        if ($subject->faculty_id && in_array($subject->faculty_id, $facultyIds)) {
            return true;
        }

        // Check through department
        if ($subject->department_id && $subject->department) {
            return in_array($subject->department->faculty_id, $facultyIds);
        }

        return false;
    }
}
