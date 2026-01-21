<?php

namespace Modules\Services\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Models\Term;
use Modules\Services\Services\ServiceRequestService;

class ServiceRequestController extends Controller
{
    public function __construct(
        private ServiceRequestService $serviceRequestService
    ) {}

    /**
     * GET /api/v1/services
     */
    public function available(): JsonResponse
    {
        $term = Term::where('is_active', true)->first();

        if (!$term) {
            return ApiResponse::notFound('No active term found');
        }

        $services = $this->serviceRequestService->getAvailableServices($term);

        return ApiResponse::success($services);
    }

    /**
     * GET /api/v1/services/my
     */
    public function myRequests(Request $request): JsonResponse
    {
        $student = $request->user();
        $term = Term::where('is_active', true)->first();

        if (!$term) {
            return ApiResponse::notFound('No active term found');
        }

        $requests = $this->serviceRequestService->getStudentRequests($student, $term);

        return ApiResponse::success($requests);
    }

    /**
     * POST /api/v1/services/request
     */
    public function submit(Request $request): JsonResponse
    {
        $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'notes' => 'nullable|string|max:1000',
            'shipping_required' => 'boolean',
        ]);

        $student = $request->user();
        $term = Term::where('is_active', true)->first();

        if (!$term) {
            return ApiResponse::notFound('No active term found');
        }

        $serviceRequest = $this->serviceRequestService->submit(
            $student,
            $term,
            $request->service_type_id,
            $request->notes,
            $request->boolean('shipping_required')
        );

        return ApiResponse::created(
            $serviceRequest->load('serviceType'),
            'Service request submitted'
        );
    }
}
