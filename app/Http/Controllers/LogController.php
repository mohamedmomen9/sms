<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class LogController extends Controller
{
    /**
     * Display a paginated list of logs with optional filters.
     */
    public function index(Request $request)
    {
        try {
            if (!Schema::hasTable('channel_logs')) {
                Log::channel('db')->warning('Tried to access logs page, but the channel_logs table does not exist.');
                abort(500, 'Logs table not found. Please run migrations first.');
            }

            $query = DB::table('channel_logs')->orderByDesc('id');

            // Apply filters
            if ($request->filled('level')) {
                $query->where('level', $request->input('level'));
            }

            if ($request->filled('from')) {
                $query->whereDate('created_at', '>=', $request->input('from'));
            }

            if ($request->filled('to')) {
                $query->whereDate('created_at', '<=', $request->input('to'));
            }

            if ($request->filled('q')) {
                $query->where(function ($sub) use ($request) {
                    $search = '%' . $request->input('q') . '%';
                    $sub->where('message', 'like', $search)
                        ->orWhere('context', 'like', $search);
                });
            }

            $logs = $query->paginate(20)->appends($request->all());

            $levels = DB::table('channel_logs')->select('level')->distinct()->pluck('level');

            return view('logs.index', compact('logs', 'levels'));
        } catch (\Exception $e) {
            return $e->getMessage();
            Log::channel('db')->error('Error loading logs: ' . $e->getMessage());
            abort(500, 'Unable to load logs.');
        }
    }

    /**
     * Show detailed view of a single log.
     */
    public function show($id)
    {
        try {
            if (!Schema::hasTable('channel_logs')) {
                Log::channel('db')->warning('Tried to view log details, but the channel_logs table does not exist.');
                abort(500, 'Logs table not found. Please run migrations first.');
            }

            $log = DB::table('channel_logs')->find($id);
            abort_unless($log, 404, 'Log entry not found.');

            return view('logs.show', compact('log'));
        } catch (\Exception $e) {
            Log::channel('db')->error('Error showing log details: ' . $e->getMessage());
            abort(500, 'Unable to display log details.');
        }
    }

    /**
     * Clear all logs.
     */
    public function clear()
    {
        try {
            DB::table('channel_logs')->delete();

            // Reset auto-increment after clearing
            DB::statement('ALTER TABLE channel_logs AUTO_INCREMENT = 1');

            return redirect()
                ->route('channel.logs.index')
                ->with('success', 'Logs cleared successfully.');
        } catch (\Exception $e) {
            abort(500, 'Unable to clear logs.');
        }
    }
}
