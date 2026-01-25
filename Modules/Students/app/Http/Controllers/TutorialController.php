<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Students\Services\TutorialService;
use Illuminate\Support\Facades\Auth;

class TutorialController extends Controller
{
    protected $tutorialService;

    public function __construct(TutorialService $tutorialService)
    {
        $this->tutorialService = $tutorialService;
    }

    public function status($key)
    {
        /** @var \Modules\Students\Models\Student $student */
        $student = Auth::user();

        $isCompleted = $this->tutorialService->isCompleted($student->id, $key);
        return response()->json(['data' => ['completed' => $isCompleted]]);
    }

    public function complete(Request $request, $key)
    {
        /** @var \Modules\Students\Models\Student $student */
        $student = Auth::user();
        $meta = $request->input('meta', []);

        $this->tutorialService->markCompleted($student->id, $key, $meta);

        return response()->json(['message' => 'Tutorial marked as completed']);
    }
}
