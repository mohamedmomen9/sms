<?php

namespace Modules\Teachers\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Config;
use Modules\Teachers\Models\Teacher;
use Kz370\JwtAuth\Services\JwtService;
use Kz370\JwtAuth\Services\RefreshTokenService;
use Kz370\JwtAuth\JwtAuthManager;
use UnexpectedValueException;
use Modules\Teachers\Transformers\LoginResource;
use App\Support\ApiResponse;
use Kz370\JwtAuth\Facades\JwtAuth;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        try {
            $decryptedPassword = $this->decryptPassword($request->password);
        } catch (Exception $e) {
            return ApiResponse::error('Security check failed: Unable to process credentials.', 400, $e->getMessage());
        }

        // Initialize config
        $config = config('jwt-auth');
        $config['user_model'] = Teacher::class;

        // Manually instantiate JWT services
        $jwtService = new JwtService($config);
        $refreshTokenService = new RefreshTokenService($config);
        $jwtAuth = new JwtAuthManager($jwtService, $refreshTokenService, $config, $request);

        $credentials = [
            'email' => $request->username,
            'password' => $decryptedPassword
        ];

        $tokenData = $jwtAuth->attempt($credentials, $request->input('device_name', 'unknown_device'));

        if ($tokenData) {
            $user = Teacher::where('email', $request->username)->first();

            return ApiResponse::success(new LoginResource($user, $tokenData), 'Staff login successful');
        }

        return ApiResponse::unauthorized('Invalid credentials');
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        try {
            $tokenData = JwtAuth::refresh($request->refresh_token);

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
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        $success = JwtAuth::logout($request->refresh_token);

        return ApiResponse::success(null, $success ? 'Logged out successfully' : 'Logout failed or token invalid');
    }

    private function decryptPassword(string $encryptedBase64): string
    {
        $keyPath = storage_path('oauth-private.key');
        
        if (!file_exists($keyPath)) {
             throw new Exception("Server encryption key not found.");
        }
        
        $privateKey = file_get_contents($keyPath);
        $encryptedData = base64_decode($encryptedBase64);
        $decryptedData = null;

        $success = openssl_private_decrypt(
            $encryptedData,
            $decryptedData,
            $privateKey,
            OPENSSL_PKCS1_OAEP_PADDING
        );

        if (!$success) {
            throw new Exception("Decryption failed.");
        }

        return $decryptedData;
    }
}
