<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\AcademicYear;
use Modules\Academic\Models\Term;
use App\Support\ApiResponse;

use Modules\Academic\Transformers\AcademicPeriodResource;

class AcademicController extends Controller
{
    public function current(Request $request)
    {
        // 1. Find Active Academic Year
        $year = AcademicYear::where('is_active', true)->first();

        // 2. Find Active Term
        $term = Term::where('is_active', true)->first();

        if (!$year && !$term) {
             return ApiResponse::notFound('No active academic period found');
        }

        // Prepare data for resource
        $data = [
            'academic_year' => $year,
            'term' => $term,
        ];

        return ApiResponse::success(new AcademicPeriodResource($data), 'Current academic period retrieved successfully');
    }
}
