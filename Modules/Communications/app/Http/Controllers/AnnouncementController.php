<?php

namespace Modules\Communications\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Communications\Models\Announcement;

class AnnouncementController extends Controller
{
    /**
     * Get announcements by type and campus.
     * @api GET /api/announcements
     */
    public function index(Request $request)
    {
        $types = $request->input('types', []);
        $campus = $request->input('campus', 'All');

        if (empty($types)) {
            $types = ['announcements', 'news', 'events'];
        }

        $results = Announcement::where(function ($q) use ($campus) {
            $q->where('campus', $campus)->orWhere('campus', 'All');
        })
            ->whereIn('type', $types)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($results);
    }

    /**
     * Find announcement by ID.
     * @api GET /api/announcements/{id}
     */
    public function show($id)
    {
        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json(['message' => 'Announcement not found'], 404);
        }

        return response()->json($announcement);
    }

    /**
     * Search announcements by campus and query.
     * @api GET /api/search/announcements
     */
    public function search(Request $request)
    {
        $campus = $request->input('campus', 'All');
        $query = $request->input('q');
        $type = $request->input('type', 'announcements'); // announcements or events

        $q = Announcement::where(function ($q) use ($campus) {
            $q->whereRaw('LOWER(campus) = ?', [strtolower($campus)])
                ->orWhereRaw('LOWER(campus) = ?', ['all']);
        });

        if ($type === 'events') {
            $q->where('type', 'events');
        } else {
            $q->whereIn('type', ['announcements', 'news']);
        }

        $results = $q->where(function ($sub) use ($query) {
            $sub->where('title', 'like', "%{$query}%")
                ->orWhere('details', 'like', "%{$query}%");
        })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($results);
    }
}
