<?php

namespace Modules\Auth\Services;

use Exception;
use Illuminate\Support\Facades\Config;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;
use Kz370\JwtAuth\Services\JwtService;
use Kz370\JwtAuth\Services\RefreshTokenService;
use Kz370\JwtAuth\Facades\JwtAuth;
use Modules\Auth\DTOs\LoginDTO;

class AuthService
{
    public function login(LoginDTO $dto): array
    {
        $decryptedPassword = $this->decryptPassword($dto->password);
        $modelClass = $this->getUserModelClass($dto->role);
        
        $config = config('jwt-auth');
        $config['user_model'] = $modelClass;
        Config::set('jwt-auth.user_model', $modelClass);

        $user = $modelClass::where('email', $dto->username)->first();
        
        if (!$user || !password_verify($decryptedPassword, $user->password)) {
            throw new Exception('Invalid credentials');
        }
        
        $jwtService = new JwtService($config);
        $refreshTokenService = new RefreshTokenService($config);
        
        $refreshTokenData = $refreshTokenService->create(
            $user,
            $dto->deviceName,
            request()->ip(),
            request()->userAgent()
        );
        
        // Role claim enables proper guard selection in UniversalJwtMiddleware
        $accessToken = $jwtService->generateAccessToken($user, [
            'role' => $dto->role,
            'rth' => hash('sha256', $refreshTokenData['token'])
        ]);
        
        $tokenData = [
            'access_token' => $accessToken,
            'refresh_token' => $refreshTokenData['token'],
            'token_type' => 'Bearer',
            'expires_in' => (int) $config['access_token_ttl'] * 60,
            'refresh_expires_in' => (int) $config['refresh_token_ttl'] * 24 * 60 * 60,
        ];

        return [
            'user' => $user,
            'tokens' => $tokenData,
            'role' => $dto->role
        ];
    }

    public function refresh(string $refreshToken)
    {
        // Facade handles polymorphic tokens via jwt_refresh_tokens table
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
