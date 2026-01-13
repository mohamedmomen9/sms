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
        $year = AcademicYear::where('is_active', true)->first();
        $term = Term::where('is_active', true)->first();

        if (!$year && !$term) {
             return ApiResponse::notFound('No active academic period found');
        }

        $data = [
            'academic_year' => $year,
            'term' => $term,
        ];

        return ApiResponse::success(new AcademicPeriodResource($data), 'Current academic period retrieved successfully');
    }
}
