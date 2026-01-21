<?php

namespace Modules\Disciplinary\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Disciplinary\Models\Grievance;
use Modules\Disciplinary\Services\GrievanceService;

class GrievanceController extends Controller
{
    public function __construct(
        private GrievanceService $grievanceService
    ) {}

    /**
     * GET /api/v1/grievances
     */
    public function index(Request $request): JsonResponse
    {
        $student = $request->user();
        $grievances = $this->grievanceService->getStudentGrievances($student);

        return response()->json([
            'success' => true,
            'data' => $grievances,
        ]);
    }

    /**
     * POST /api/v1/grievances/{id}/appeal
     */
    public function submitAppeal(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'appeal_text' => 'required|string|max:2000',
        ]);

        $student = $request->user();
        $grievance = Grievance::where('id', $id)
            ->where('student_id', $student->student_id)
            ->firstOrFail();

        if (!$this->grievanceService->canSubmitAppeal($grievance)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot submit appeal for this grievance',
            ], 409);
        }

        $this->grievanceService->submitAppeal($grievance, $request->appeal_text);

        return response()->json([
            'success' => true,
            'message' => 'Appeal submitted',
        ]);
    }
}
