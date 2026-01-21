<?php

namespace Modules\Disciplinary\Services;

use Illuminate\Support\Collection;
use Modules\Disciplinary\Models\Grievance;
use Modules\Students\Models\Student;

class GrievanceService
{
    /**
     * Get student's grievances.
     */
    public function getStudentGrievances(Student $student): Collection
    {
        return Grievance::query()
            ->where('student_id', $student->student_id)
            ->where('approval_status', 'approved')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($g) => [
                'id' => $g->id,
                'violation_type' => $g->violation_type,
                'decision_text' => $g->decision_text,
                'dean_date' => $g->dean_date?->format('Y-m-d'),
                'grievance_decision' => $g->grievance_approval_status !== 'pending'
                    ? $g->grievance_decision
                    : null,
                'grievance_dean_date' => $g->grievance_approval_status !== 'pending'
                    ? $g->grievance_dean_date?->format('Y-m-d')
                    : null,
                'can_submit_appeal' => $this->canSubmitAppeal($g),
            ]);
    }

    /**
     * Check if student can submit appeal.
     */
    public function canSubmitAppeal(Grievance $grievance): bool
    {
        return $grievance->grievance_approval_status === null
            && empty($grievance->grievance_text);
    }

    /**
     * Submit grievance appeal.
     */
    public function submitAppeal(Grievance $grievance, string $appealText): bool
    {
        return $grievance->update([
            'grievance_text' => $appealText,
            'grievance_approval_status' => 'pending',
        ]);
    }
}
