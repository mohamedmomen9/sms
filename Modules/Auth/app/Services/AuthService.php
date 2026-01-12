<?php

namespace Modules\Auth\Services;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;
use Kz370\JwtAuth\Services\JwtService;
use Kz370\JwtAuth\Services\RefreshTokenService;
use Kz370\JwtAuth\JwtAuthManager;
use Kz370\JwtAuth\Facades\JwtAuth;
use Modules\Auth\DTOs\LoginDTO;

class AuthService
{
    /**
     * Authenticate a user based on role and credentials.
     */
    public function login(LoginDTO $dto): array
    {
        // 1. Decrypt Password
        $decryptedPassword = $this->decryptPassword($dto->password);
        
        // 2. Resolve Model and Config based on Role
        $modelClass = $this->getUserModelClass($dto->role);
        
        // 3. Configure JWT dynamically
        $config = config('jwt-auth');
        $config['user_model'] = $modelClass;
        
        // Update global config as well for any side-effects
        Config::set('jwt-auth.user_model', $modelClass);

        // 4. Instantiate JWT Manager
        $jwtService = new JwtService($config);
        $refreshTokenService = new RefreshTokenService($config);
        // We need the request object for JwtAuthManager, but better to use Facade or construct logic that returns tokens directly.
        // The JwtAuthManager::attempt method requires the request object in the constructor.
        // Let's rely on the manual instantiation pattern we found reliable.
        $jwtAuth = new JwtAuthManager($jwtService, $refreshTokenService, $config, request());

        $credentials = [
            'email' => $dto->username,
            'password' => $decryptedPassword
        ];

        // 5. Attempt Authentication
        $tokenData = $jwtAuth->attempt($credentials, $dto->deviceName);

        if (!$tokenData) {
            throw new Exception('Invalid credentials');
        }

        // 6. Retrieve User
        $user = $modelClass::where('email', $dto->username)->first();

        return [
            'user' => $user,
            'tokens' => $tokenData,
            'role' => $dto->role
        ];
    }

    public function refresh(string $refreshToken)
    {
        // Refresh logic is generic enough to use the Facade usually, but if we encounter issues we might need context.
        // For now, use Facade as it works for polymorphic tokens if `jwt_refresh_tokens` table is polymorphic (it is).
        return JwtAuth::refresh($refreshToken);
    }

    public function logout(string $refreshToken)
    {
        return JwtAuth::logout($refreshToken);
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

    private function getUserModelClass(string $role): string
    {
        return match ($role) {
            'student' => Student::class,
            'teacher', 'staff' => Teacher::class, // Handle aliases
            default => throw new Exception("Unknown role: {$role}")
        };
    }
}
