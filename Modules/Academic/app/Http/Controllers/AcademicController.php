<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\AcademicYear;
use Modules\Academic\Models\Term;
use App\Support\ApiResponse;

class AcademicController extends Controller
{
    public function current(Request $request)
    {
        // 1. Find Active Academic Year
        $year = AcademicYear::where('is_active', true)->first();

        // 2. Find Active Term
        // Often there is only ONE active term globally. 
        // Or we might want the active term belonging to the active year.
        $term = Term::where('is_active', true)->first();

        if (!$year && !$term) {
             return ApiResponse::notFound('No active academic period found');
        }

        return ApiResponse::success([
            'academic_year' => $year ? [
                'id' => $year->id,
                'name' => $year->name,
                'start_date' => $year->start_date,
                'end_date' => $year->end_date,
            ] : null,
            'term' => $term ? [
                'id' => $term->id,
                'name' => $term->name,
                'code' => $term->code,
                'start_date' => $term->start_date,
                'end_date' => $term->end_date,
                'type' => $term->type
            ] : null,
        ], 'Current academic period retrieved successfully');
    }
}
