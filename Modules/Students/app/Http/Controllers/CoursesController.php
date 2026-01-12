<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Students\Transformers\CourseResource;
use App\Support\ApiResponse;

class CoursesController extends Controller
{
    public function current(Request $request)
    {
        /** @var \Modules\Students\Models\Student|null $student */
        $student = Auth::guard('student')->user();

        if (!$student) {
            return ApiResponse::unauthorized('Student not found');
        }

        // Fetch current enrollments:
        // 'currentClasses' relies on 'courseOffering.term.is_active' = true
        // We eager load necessary relations for the Resource
        $enrollments = $student->currentClasses()
            ->with(['courseOffering.subject', 'courseOffering.teacher', 'courseOffering.room', 'courseOffering.term'])
            ->get();

        return ApiResponse::success(CourseResource::collection($enrollments), 'Current courses retrieved successfully');
    }
}
