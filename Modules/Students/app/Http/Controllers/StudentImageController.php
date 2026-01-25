<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Students\Models\StudentImage;

class StudentImageController extends Controller
{
    /**
     * Get student face recognition status.
     * @api GET /api/student-images/eligibility
     */
    public function eligibility(Request $request)
    {
        $studentId = $request->input('student_id');
        if (!$studentId) {
            return response()->json(['message' => 'Student ID required'], 400);
        }

        // logic from DashboardEloquentQueries uses static method faceRecognitionStatus
        // Check if that method exists in model, otherwise assume standard query
        if (method_exists(StudentImage::class, 'faceRecognitionStatus')) {
            return response()->json(StudentImage::faceRecognitionStatus($studentId));
        }

        // Fallback or implementation
        $status = StudentImage::where('cicid', $studentId)->exists(); // Simplified
        return response()->json(['active' => $status]);
    }

    /**
     * Update or create student image.
     * @api POST /api/student-images
     */
    public function update(Request $request)
    {
        $studentId = $request->input('cicid');

        if (!$studentId) {
            return response()->json(['message' => 'CICID required'], 400);
        }

        $imageData = $request->all();
        unset($imageData['cicid']); // Prevent updating key if passed in body too

        $image = StudentImage::updateOrCreate(
            ['cicid' => $studentId],
            $imageData
        );

        return response()->json($image);
    }

    /**
     * Get student image by CICID.
     * @api GET /api/student-images/{cicid}
     */
    public function show($cicid)
    {
        $image = StudentImage::where('cicid', $cicid)->first();
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        return response()->json($image);
    }
}
