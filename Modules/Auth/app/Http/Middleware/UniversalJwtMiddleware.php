<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kz370\JwtAuth\Facades\JwtAuth;
use Exception;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class UniversalJwtMiddleware
{
    /**
     * Handle an incoming request.
     * This middleware tries to authenticate the user as EITHER a Student OR a Teacher.
     */
    public function handle(Request $request, Closure $next)
    {
        // We need to determine the type. 
        // 1. Check if token works for Student
        if ($this->attemptGuard($request, 'student', Student::class)) {
            return $next($request);
        }

        // 2. Check if token works for Teacher
        if ($this->attemptGuard($request, 'teacher', Teacher::class)) {
            return $next($request);
        }

        // 3. Fail
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized: Invalid token or user type.'
        ], 401);
    }

    private function attemptGuard(Request $request, string $guardName, string $modelClass): bool
    {
        try {
            // Configure JWT for this model
            Config::set('jwt-auth.user_model', $modelClass);
            
            // Re-instantiate service (lite version of what we did in StudentJwtAuth)
            // Ideally we should use a Service to do this verification to avoid duplication,
            // but for Middleware speed we do it inline or helper.
            
            // NOTE: The 'jwt' guard instance is singleton. We must refresh it or use Facade carefully.
            // The safest way with this package without deep hacking is:
            
            // 1. Manually check token using JwtService directly
            $config = config('jwt-auth'); 
            $config['user_model'] = $modelClass;
            $jwtService = new \Kz370\JwtAuth\Services\JwtService($config);
            
            // 2. Parse and Validate
            $token = $request->bearerToken();
            if (!$token) return false;

            $payload = $jwtService->validateAccessToken($token);
            if (!$payload) return false;

            // 3. Check if user exists
            $user = $modelClass::find($payload['sub']);
            if (!$user) return false;

            // 4. Success -> Set User to Auth
            Auth::guard($guardName)->setUser($user);
            Auth::shouldUse($guardName); // Make Auth::user() return this user
            
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
}
