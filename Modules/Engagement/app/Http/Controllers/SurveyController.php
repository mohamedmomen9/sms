<?php

namespace Modules\Engagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Engagement\Models\Survey;

class SurveyController extends Controller
{
    /**
     * Get active surveys.
     * @api GET /api/surveys
     */
    public function index()
    {
        $surveys = Survey::where('active', 1)->get();
        return response()->json($surveys);
    }

    /**
     * Get a specific survey by ID.
     * @api GET /api/surveys/{id}
     */
    public function show($id)
    {
        $survey = Survey::find($id);
        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }
        return response()->json($survey);
    }
}
