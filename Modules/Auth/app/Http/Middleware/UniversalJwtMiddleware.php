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
     * Try to authenticate as Student or Teacher based on the JWT token.
     */
    public function handle(Request $request, Closure $next)
    {
        // Try student first, then teacher
        if ($this->attemptGuard($request, 'student', Student::class)) {
            return $next($request);
        }

        if ($this->attemptGuard($request, 'teacher', Teacher::class)) {
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized: Invalid token or user type.'
        ], 401);
    }

    private function attemptGuard(Request $request, string $guardName, string $modelClass): bool
    {
        try {
            Config::set('jwt-auth.user_model', $modelClass);
            
            $config = config('jwt-auth'); 
            $config['user_model'] = $modelClass;
            $jwtService = new \Kz370\JwtAuth\Services\JwtService($config);
            
            $token = $request->bearerToken();
            if (!$token) return false;

            $payload = $jwtService->validateAccessToken($token);
            if (!$payload) return false;

            $user = $modelClass::find($payload['sub']);
            if (!$user) return false;

            // Set the authenticated user on the appropriate guard
            Auth::guard($guardName)->setUser($user);
            Auth::shouldUse($guardName);
            
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
}
