<?php

namespace Modules\Admissions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admissions\Models\Applicant;

class ApplicantController extends Controller
{
    /**
     * Find applicant for login.
     * @api POST /api/applicants/login
     */
    public function login(Request $request)
    {
        $phone = $request->input('phone');
        $password = $request->input('password');

        if (!$phone || !$password) {
            return response()->json(['message' => 'Phone and password required'], 400);
        }

        // Logic from DashboardEloquentQueries:
        // Applicant::where('phone', ltrim($phone, '0'))
        //     ->where('password', base64_decode($password)) ...
        // Wait, base64_decode($password)? That implies the client sends base64 encoded password?
        // Detailed implementation:

        $applicant = Applicant::where('phone', ltrim($phone, '0'))
            ->where('password', base64_decode($password))
            ->first();

        if (!$applicant) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json($applicant);
    }
}
