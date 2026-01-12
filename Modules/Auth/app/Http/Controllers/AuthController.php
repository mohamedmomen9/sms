<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\Services\AuthService;
use Modules\Auth\DTOs\LoginDTO;
use App\Support\ApiResponse;
use Exception;
use Modules\Students\Transformers\LoginResource; // Reusing this for now, or create a generic one

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request, string $role = null)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        // Determine role from route param or body
        $role = $role ?? $request->input('role');
        
        if (!$role) {
            return ApiResponse::error('Role is required', 400);
        }
        
        // Normalize role
        $role = strtolower($role);

        try {
            $dto = new LoginDTO(
                $request->username,
                $request->password,
                $request->input('device_name', 'unknown_device'),
                $role
            );

            $result = $this->authService->login($dto);

            // Return standardized response
            // We can reuse the Transformers if valid, or a generic structure
            // Since we promised a generic structure, let's keep it clean
            
            return ApiResponse::success([
                'user' => $result['user'],
                'tokens' => $result['tokens'],
                'role' => $result['role']
            ], ucfirst($role) . ' login successful');

        } catch (Exception $e) {
            // Check for specific error message to determine code
            $code = $e->getMessage() === 'Invalid credentials' ? 401 : 400;
            return ApiResponse::error($e->getMessage(), $code);
        }
    }

    public function refresh(Request $request)
    {
        $request->validate(['refresh_token' => 'required|string']);

        try {
            $tokenData = $this->authService->refresh($request->refresh_token);

            if (!$tokenData) {
                return ApiResponse::unauthorized('Invalid or expired refresh token');
            }

            return ApiResponse::success($tokenData, 'Token refreshed successfully');
        } catch (Exception $e) {
            return ApiResponse::unauthorized('Token refresh failed');
        }
    }

    public function logout(Request $request)
    {
        $request->validate(['refresh_token' => 'required|string']);

        $success = $this->authService->logout($request->refresh_token);

        return ApiResponse::success(null, $success ? 'Logged out successfully' : 'Logout failed');
    }
}
