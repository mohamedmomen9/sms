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
        $user = $request->user();



        $studentId = $request->authenticated_student_id ?? $user->student_id;

        if (!$studentId) {
            return response()->json(['message' => 'Student ID required'], 400);
        }

        if (method_exists(StudentImage::class, 'faceRecognitionStatus')) {
            return response()->json(StudentImage::faceRecognitionStatus($studentId));
        }

        $status = StudentImage::where('student_id', $studentId)->exists();
        return response()->json(['active' => $status]);
    }

    /**
     * Update or create student image.
     * @api POST /api/student-images
     */
    public function update(Request $request)
    {
        $user = $request->user();


        $studentId = $request->authenticated_student_id ?? $user->student_id;

        if (!$studentId) {
            return response()->json(['message' => 'Student ID required'], 400);
        }

        $imageData = $request->all();


        $image = StudentImage::updateOrCreate(
            ['student_id' => $studentId],
            $imageData
        );

        return response()->json($image);
    }

    /**
     * Get student image by Student ID.
     * @api GET /api/student-images/{studentId}
     */
    public function show($studentId)
    {
        $image = StudentImage::where('student_id', $studentId)->first();
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        return response()->json($image);
    }
}
