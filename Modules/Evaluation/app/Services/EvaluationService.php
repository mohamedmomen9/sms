<?php

namespace Modules\Evaluation\Services;

use Illuminate\Support\Collection;
use Modules\Evaluation\Models\Assessment;
use Modules\Evaluation\Models\Evaluation;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;
use Modules\Subject\Models\CourseOffering;

class EvaluationService
{
    /**
     * Get active assessment with structure.
     */
    public function getAssessmentStructure(string $category = 'course'): ?array
    {
        $assessment = Assessment::active()
            ->forCategory($category)
            ->with(['categories.questions', 'rates'])
            ->first();

        if (!$assessment) {
            return null;
        }

        return [
            'assessment' => [
                'id' => $assessment->id,
                'name' => $assessment->name,
            ],
            'categories' => $assessment->categories->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'questions' => $cat->questions->map(fn($q) => [
                    'id' => $q->id,
                    'question' => $q->question,
                ]),
            ]),
            'rates' => $assessment->rates->map(fn($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'weight' => $r->weight,
            ]),
        ];
    }

    /**
     * Get courses available for evaluation.
     */
    public function getEvaluableCourses(Student $student, Term $term): Collection
    {
        return CourseOffering::query()
            ->where('term_id', $term->id)
            ->whereHas('enrollments', fn($q) => $q->where('student_id', $student->id))
            ->with(['subject', 'teachers'])
            ->get()
            ->map(fn($offering) => [
                'course_code' => $offering->subject->code,
                'course_name' => $offering->subject->name,
                'instructors' => $offering->teachers->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                ]),
            ]);
    }

    /**
     * Check if student has submitted evaluation.
     */
    public function hasSubmitted(
        Student $student,
        int $assessmentId,
        Term $term,
        string $courseCode,
        int $instructorId
    ): bool {
        return Evaluation::query()
            ->where('student_id', $student->student_id)
            ->where('assessment_id', $assessmentId)
            ->where('term_id', $term->id)
            ->where('course_code', $courseCode)
            ->where('instructor_id', $instructorId)
            ->exists();
    }

    /**
     * Submit evaluation.
     */
    public function submit(
        Student $student,
        int $assessmentId,
        Term $term,
        string $courseCode,
        int $instructorId,
        array $responses
    ): Evaluation {
        return Evaluation::create([
            'student_id' => $student->student_id,
            'assessment_id' => $assessmentId,
            'term_id' => $term->id,
            'course_code' => $courseCode,
            'instructor_id' => $instructorId,
            'responses' => $responses,
            'submitted_at' => now(),
        ]);
    }
}
