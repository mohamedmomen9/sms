<?php

namespace Modules\System\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class InjectUserContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user) {
            // Determine user type and inject IDs
            if ($user instanceof Student) {
                $request->merge([
                    'authenticated_student_id' => $user->student_id ?? $user->id,
                    'is_student' => true,
                ]);
            } elseif ($user instanceof Teacher) {
                $request->merge([
                    'authenticated_instructor_id' => $user->id,
                    'is_instructor' => true,
                ]);
            }
        }

        return $next($request);
    }
}
