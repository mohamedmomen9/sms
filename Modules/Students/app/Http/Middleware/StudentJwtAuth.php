<?php

namespace Modules\Students\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Kz370\JwtAuth\Http\Middleware\JwtAuthenticate;
use Modules\Students\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentJwtAuth
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Configure JWT for Student Model
        Config::set('jwt-auth.user_model', Student::class);

        // 2. Use the package's middleware to validate token and set user
        // We use the container to resolve the middleware to ensure dependencies are injected
        return app(JwtAuthenticate::class)->handle($request, function ($request) use ($next) {
            
            // 3. Ensure the authenticated user is actually a Student
            // The package middleware sets the user on the configured guard (default 'jwt')
            // We want to make it available via Auth::guard('student') as well for convenience
            
            $user = Auth::guard('jwt')->user(); // Default guard used by package
            
            if (!$user || !($user instanceof Student)) {
                return response()->json(['message' => 'Unauthorized: Valid student token required'], 401);
            }

            // Sync with 'student' guard so our controllers work as expected
            Auth::guard('student')->setUser($user);

            return $next($request);
        });
    }
}
