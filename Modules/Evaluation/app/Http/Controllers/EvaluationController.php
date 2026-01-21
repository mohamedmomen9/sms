<?php

namespace Modules\Evaluation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Models\Term;
use Modules\Evaluation\Services\EvaluationService;

class EvaluationController extends Controller
{
    public function __construct(
        private EvaluationService $evaluationService
    ) {}

    /**
     * GET /api/v1/evaluation/structure
     */
    public function structure(Request $request): JsonResponse
    {
        $category = $request->get('category', 'course');
        $structure = $this->evaluationService->getAssessmentStructure($category);

        if (!$structure) {
            return ApiResponse::notFound('No active assessment found');
        }

        return ApiResponse::success($structure);
    }

    /**
     * GET /api/v1/evaluation/courses
     */
    public function courses(Request $request): JsonResponse
    {
        $student = $request->user();
        $term = Term::where('is_active', true)->firstOrFail();

        $courses = $this->evaluationService->getEvaluableCourses($student, $term);

        return ApiResponse::success($courses);
    }

    /**
     * POST /api/v1/evaluation/submit
     */
    public function submit(Request $request): JsonResponse
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'course_code' => 'required|string',
            'instructor_id' => 'required|exists:teachers,id',
            'responses' => 'required|array',
            'responses.*.question_id' => 'required|exists:assessment_questions,id',
            'responses.*.rate_id' => 'required|exists:assessment_rates,id',
        ]);

        $student = $request->user();
        $term = Term::where('is_active', true)->firstOrFail();

        if ($this->evaluationService->hasSubmitted(
            $student,
            $request->assessment_id,
            $term,
            $request->course_code,
            $request->instructor_id
        )) {
            return ApiResponse::error('Evaluation already submitted', 409);
        }

        $evaluation = $this->evaluationService->submit(
            $student,
            $request->assessment_id,
            $term,
            $request->course_code,
            $request->instructor_id,
            $request->responses
        );

        return ApiResponse::created($evaluation, 'Evaluation submitted successfully');
    }
}
