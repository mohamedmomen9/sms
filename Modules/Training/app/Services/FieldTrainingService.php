<?php

namespace Modules\Training\Services;

use Illuminate\Support\Collection;
use Modules\Training\Models\TrainingOpportunity;
use Modules\Training\Models\FieldTraining;
use Modules\Training\Models\StudentWishlist;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class FieldTrainingService
{
    /**
     * Get available training opportunities.
     */
    public function getAvailableOpportunities(
        ?int $facultyId = null,
        ?int $departmentId = null,
        ?string $concentration = null,
        ?string $cohort = null
    ): Collection {
        return TrainingOpportunity::available()
            ->when($facultyId, fn($q) => $q->where('faculty_id', $facultyId))
            ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
            ->when($concentration, fn($q) => $q->where('concentration', $concentration))
            ->when($cohort, fn($q) => $q->where('cohort', $cohort))
            ->with(['faculty', 'department'])
            ->get();
    }

    /**
     * Get training conditions and documents.
     */
    public function getOpportunityRequirements(TrainingOpportunity $opportunity): array
    {
        return [
            'conditions' => $opportunity->conditions ?? [],
            'required_documents' => $opportunity->required_documents ?? [],
        ];
    }

    /**
     * Apply for training.
     */
    public function apply(
        Student $student,
        TrainingOpportunity $opportunity,
        Term $term
    ): FieldTraining {
        return FieldTraining::create([
            'student_id' => $student->student_id,
            'opportunity_id' => $opportunity->id,
            'term_id' => $term->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Get student's wishlist votes.
     */
    public function getStudentWishlist(Student $student, Term $term): ?StudentWishlist
    {
        return StudentWishlist::query()
            ->where('student_id', $student->student_id)
            ->where('term_id', $term->id)
            ->first();
    }

    /**
     * Submit wishlist vote.
     */
    public function submitWishlist(
        Student $student,
        Term $term,
        array $itemIds,
        array $itemNames
    ): StudentWishlist {
        return StudentWishlist::updateOrCreate(
            [
                'student_id' => $student->student_id,
                'term_id' => $term->id,
            ],
            [
                'item_ids' => $itemIds,
                'item_names' => $itemNames,
            ]
        );
    }
}
