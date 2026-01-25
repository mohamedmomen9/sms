<?php

namespace Modules\Communications\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Communications\Models\Notification;
use Modules\Communications\Models\NotificationLog;

class NotificationController extends Controller
{
    /**
     * Get student notifications with read status.
     * @api GET /api/notifications
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            // Fallback for demo/testing if auth not fully set up yet, or return 401
            // For now, let's assume student_id is passed or handled via auth
            // Using logic from DashboardEloquentQueries: getStudentNotificationsWithLogs($studentId)
            $studentId = $request->input('student_id');
        } else {
            $studentId = $user->cicid; // Assuming user has cicid
        }

        if (!$studentId) {
            return response()->json(['message' => 'Student ID required'], 400);
        }

        $results = Notification::select('notifications.*', 'notification_logs.is_read')
            ->join('notification_logs', 'notification_logs.notification_id', '=', 'notifications.id')
            ->where('student_id', $studentId)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($results);
    }

    /**
     * Get a single notification by ID.
     * @api GET /api/notifications/{id}
     */
    public function show($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }
        return response()->json($notification);
    }

    /**
     * Mark a notification as read.
     * @api POST /api/notifications/{id}/read
     */
    public function markAsRead(Request $request, $id)
    {
        $studentId = $request->input('student_id'); // Or from auth

        if (!$studentId) {
            return response()->json(['message' => 'Student ID required'], 400);
        }

        $log = NotificationLog::updateOrCreate(
            [
                'notification_id' => $id,
                'student_id' => $studentId,
            ],
            [
                'is_read' => true,
            ]
        );

        return response()->json($log);
    }

    /**
     * Get instructor notifications with read status.
     * @api GET /api/instructor/notifications
     */
    public function instructorIndex(Request $request)
    {
        $instructorId = $request->input('instructor_id'); // Or from auth

        if (!$instructorId) {
            return response()->json(['message' => 'Instructor ID required'], 400);
        }

        $results = Notification::select('notifications.*', 'notification_logs.is_read')
            ->join('notification_logs', 'notification_logs.notification_id', '=', 'notifications.id')
            ->where('people_id', $instructorId)
            ->where(function ($query) {
                $query->whereNull('student_id')
                    ->orWhere('student_id', '');
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($results);
    }
}
