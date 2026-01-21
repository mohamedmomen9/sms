<?php

namespace Modules\Training\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Models\Term;
use Modules\Training\Models\TrainingOpportunity;
use Modules\Training\Services\FieldTrainingService;

class FieldTrainingController extends Controller
{
    public function __construct(
        private FieldTrainingService $trainingService
    ) {}

    /**
     * GET /api/v1/training/opportunities
     */
    public function opportunities(Request $request): JsonResponse
    {
        $opportunities = $this->trainingService->getAvailableOpportunities(
            $request->faculty_id,
            $request->department_id,
            $request->concentration,
            $request->cohort
        );

        return response()->json([
            'success' => true,
            'data' => $opportunities,
        ]);
    }

    /**
     * POST /api/v1/training/apply
     */
    public function apply(Request $request): JsonResponse
    {
        $request->validate([
            'opportunity_id' => 'required|exists:training_opportunities,id',
        ]);

        $student = $request->user();
        $term = Term::where('is_active', true)->firstOrFail();
        $opportunity = TrainingOpportunity::findOrFail($request->opportunity_id);

        $application = $this->trainingService->apply($student, $opportunity, $term);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted',
            'data' => $application,
        ], 201);
    }

    /**
     * GET /api/v1/training/wishlist
     */
    public function getWishlist(Request $request): JsonResponse
    {
        $student = $request->user();
        $term = Term::where('is_active', true)->firstOrFail();

        $wishlist = $this->trainingService->getStudentWishlist($student, $term);

        return response()->json([
            'success' => true,
            'data' => $wishlist,
        ]);
    }

    /**
     * POST /api/v1/training/wishlist
     */
    public function submitWishlist(Request $request): JsonResponse
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_names' => 'required|array',
        ]);

        $student = $request->user();
        $term = Term::where('is_active', true)->firstOrFail();

        $wishlist = $this->trainingService->submitWishlist(
            $student,
            $term,
            $request->item_ids,
            $request->item_names
        );

        return response()->json([
            'success' => true,
            'message' => 'Wishlist submitted',
            'data' => $wishlist,
        ], 201);
    }
}
