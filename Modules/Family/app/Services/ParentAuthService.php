<?php

namespace Modules\Family\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Family\Models\Guardian;
use Modules\Family\Models\ParentVerification;

class ParentAuthService
{
    public function register(array $parentData, int $studentId): Guardian
    {
        $parentData['student_id'] = $studentId;
        // Password optional
        if (! isset($parentData['password'])) {
            $parentData['password'] = Hash::make(Str::random(16));
        } else {
            $parentData['password'] = Hash::make($parentData['password']);
        }

        return Guardian::create($parentData);
    }

    public function generateOtp(int $parentId): string
    {
        $otp = (string) rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        // Hashing OTP for security
        ParentVerification::create([
            'parent_id' => $parentId,
            'phone' => Guardian::find($parentId)->phone,
            'otp' => Hash::make($otp),
            'otp_expires_at' => $expiresAt,
        ]);

        // Send OTP via SMS
        // Notification::send($parent, new SendOtpNotification($otp));

        return $otp;
    }

    public function verifyOtp(int $parentId, string $otp): bool
    {
        $verification = ParentVerification::where('parent_id', $parentId)
            ->where('otp_expires_at', '>', now())
            ->latest()
            ->first();

        if (! $verification) {
            return false;
        }

        if (Hash::check($otp, $verification->otp)) {
            $verification->update([
                'verified_at' => now(),
                'otp' => null, // Clear OTP
            ]);
            return true;
        }

        return false;
    }

    public function login(string $phone): ?Guardian
    {
        // Simple lookup, logic handled via OTP
        return Guardian::where('phone', $phone)->first();
    }
}
