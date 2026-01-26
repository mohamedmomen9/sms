<?php

namespace Modules\Communications\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Communications\Models\Notification;
use Modules\Communications\Models\NotificationLog;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;

class NotificationController extends Controller
{
    /**
     * Get student notifications with read status.
     * @api GET /api/notifications
     */
    public function index(Request $request)
    {
        $user = $request->user();



        $results = Notification::select('notifications.*', 'notification_logs.is_read')
            ->join('notification_logs', 'notification_logs.notification_id', '=', 'notifications.id')
            ->where('notification_logs.notifiable_type', Student::class)
            ->where('notification_logs.notifiable_id', $user->id)
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
        $user = $request->user();

        if (!Notification::where('id', $id)->exists()) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $log = NotificationLog::updateOrCreate(
            [
                'notification_id' => $id,
                'notifiable_type' => Student::class,
                'notifiable_id' => $user->id,
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
        $user = $request->user();


        $results = Notification::select('notifications.*', 'notification_logs.is_read')
            ->join('notification_logs', 'notification_logs.notification_id', '=', 'notifications.id')
            ->where('notification_logs.notifiable_type', Teacher::class)
            ->where('notification_logs.notifiable_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($results);
    }
}
