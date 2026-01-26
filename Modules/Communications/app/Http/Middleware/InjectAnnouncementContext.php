<?php

namespace Modules\Communications\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Campus\Models\Campus;

class InjectAnnouncementContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Set default types if none provided
        $types = $request->input('types', []);
        if (empty($types)) {
            $types = ['announcements', 'news', 'events'];
        }

        $campusInput = $request->input('campus');
        $campusId = null;

        // Resolve campus ID from input
        if ($campusInput && $campusInput !== 'All') {
            if (is_numeric($campusInput)) {
                $campusId = (int)$campusInput;
            } else {
                // Match campus by name
                $campus = Campus::where('name', 'like', "%{$campusInput}%")->first();
                if ($campus) {
                    $campusId = $campus->id;
                }
            }
        }

        // Default to user's campus if not specified
        if ($campusId === null && $user && !$campusInput) {
            if ($user->faculty && $user->faculty->campus_id) {
                $campusId = $user->faculty->campus_id;
            }
        }

        if ($user) {
            $request->merge([
                'user_id' => $user->id,
                'user_name' => $user->name,
            ]);
        }

        // Inject context into request
        $request->merge([
            'types' => $types,
            'campus' => $campusInput ?? 'All',
            'campus_id' => $campusId,
        ]);

        return $next($request);
    }
}
