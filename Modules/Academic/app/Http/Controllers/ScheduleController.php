<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Academic\Services\ScheduleService;
use Modules\Academic\Transformers\ScheduleCollection;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;

class ScheduleController extends Controller
{
    protected ScheduleService $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function studentSchedule(Request $request)
    {
        $user = Auth::guard('student')->user();

        if (!$user || !($user instanceof Student)) {
            return ApiResponse::unauthorized('Student not authenticated. Please login as a student.');
        }

        $schedule = $this->scheduleService->getStudentSchedule($user);

        if ($schedule->isEmpty()) {
            return ApiResponse::success([], 'No schedule found for the current term');
        }

        $groupBy = $request->get('group_by', 'course'); // Options: day, flat, course (default)
        
        if ($groupBy === 'day') {
            $grouped = $this->scheduleService->groupScheduleByDay($schedule);
            return ApiResponse::success($grouped, 'Student schedule retrieved successfully (grouped by day)');
        }
        
        if ($groupBy === 'flat') {
            return ApiResponse::success(
                new ScheduleCollection($schedule),
                'Student schedule retrieved successfully'
            );
        }

        $grouped = $this->scheduleService->groupScheduleByCourse($schedule);
        return ApiResponse::success($grouped, 'Student schedule retrieved successfully');
    }

    public function studentTodaySchedule(Request $request)
    {
        $user = Auth::guard('student')->user();

        if (!$user || !($user instanceof Student)) {
            return ApiResponse::unauthorized('Student not authenticated. Please login as a student.');
        }

        $schedule = $this->scheduleService->getStudentTodaySchedule($user);

        return ApiResponse::success(
            new ScheduleCollection($schedule),
            'Today\'s schedule retrieved successfully'
        );
    }

    public function teacherSchedule(Request $request)
    {
        $user = Auth::guard('teacher')->user();

        if (!$user || !($user instanceof Teacher)) {
            return ApiResponse::unauthorized('Teacher not authenticated. Please login as a teacher.');
        }

        $schedule = $this->scheduleService->getTeacherSchedule($user);

        if ($schedule->isEmpty()) {
            return ApiResponse::success([], 'No schedule found for the current term');
        }

        if ($request->boolean('grouped')) {
            $grouped = $this->scheduleService->groupScheduleByDay($schedule);
            return ApiResponse::success($grouped, 'Teacher schedule retrieved successfully (grouped by day)');
        }

        return ApiResponse::success(
            new ScheduleCollection($schedule),
            'Teacher schedule retrieved successfully'
        );
    }

    public function teacherTodaySchedule(Request $request)
    {
        $user = Auth::guard('teacher')->user();

        if (!$user || !($user instanceof Teacher)) {
            return ApiResponse::unauthorized('Teacher not authenticated. Please login as a teacher.');
        }

        $schedule = $this->scheduleService->getTeacherTodaySchedule($user);

        return ApiResponse::success(
            new ScheduleCollection($schedule),
            'Today\'s schedule retrieved successfully'
        );
    }
}

