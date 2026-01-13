<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Kz370\JwtAuth\Services\JwtService;

class UniversalJwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return $this->unauthorizedResponse();
        }
        
        $config = config('jwt-auth');
        $jwtService = new JwtService($config);
        $payload = $jwtService->parseToken($token);
        
        if (!$payload || !isset($payload['role'])) {
            return $this->handleLegacyToken($request, $next, $token);
        }
        
        // Use role claim to select correct guard (avoids student/teacher ID collisions)
        $role = $payload['role'];
        if ($role === 'student' && $this->authenticateAs($request, 'student', Student::class)) {
            return $next($request);
        }
        
        if (in_array($role, ['teacher', 'staff']) && $this->authenticateAs($request, 'teacher', Teacher::class)) {
            return $next($request);
        }
        
        return $this->unauthorizedResponse();
    }
    
    private function authenticateAs(Request $request, string $guardName, string $modelClass): bool
    {
        try {
            $config = config('jwt-auth');
            $config['user_model'] = $modelClass;
            Config::set('jwt-auth.user_model', $modelClass);
            
            $jwtService = new JwtService($config);
            $token = $request->bearerToken();
            
            $payload = $jwtService->validateAccessToken($token);
            if (!$payload) {
                return false;
            }
            
            $user = $modelClass::find($payload['sub']);
            if (!$user) {
                return false;
            }
            
            Auth::guard($guardName)->setUser($user);
            Auth::shouldUse($guardName);
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /** @deprecated For tokens issued before role claim was added */
    private function handleLegacyToken(Request $request, Closure $next, string $token): mixed
    {
        if ($this->authenticateAs($request, 'student', Student::class)) {
            return $next($request);
        }
        
        if ($this->authenticateAs($request, 'teacher', Teacher::class)) {
            return $next($request);
        }
        
        return $this->unauthorizedResponse();
    }
    
    private function unauthorizedResponse()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized: Invalid token or user type.'
        ], 401);
    }
}

