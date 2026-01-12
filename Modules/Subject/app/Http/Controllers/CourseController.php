<?php

namespace Modules\Subject\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Subject\Factories\CourseServiceFactory;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        try {
            // User is injected by UniversalJwtMiddleware -> Auth::user() works
            $user = Auth::user();

            if (!$user) {
                return ApiResponse::unauthorized('User context not found');
            }

            $service = CourseServiceFactory::make($user);
            $courses = $service->getCurrentCourses();

            return ApiResponse::success($courses, 'Current courses retrieved successfully');

        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
