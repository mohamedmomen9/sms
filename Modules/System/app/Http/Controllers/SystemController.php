<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Models\AppVersion;
use Modules\System\Services\UserAgreementService;
use Illuminate\Support\Facades\Auth;

class SystemController extends Controller
{
    protected $agreementService;

    public function __construct(UserAgreementService $agreementService)
    {
        $this->agreementService = $agreementService;
    }

    public function appVersion(Request $request)
    {
        $platform = $request->query('platform', 'ios');
        $version = AppVersion::where('platform', $platform)->first();

        if (!$version) {
            return response()->json(['message' => 'Version not found'], 404);
        }

        return response()->json([
            'data' => [
                'platform' => $version->platform,
                'current_version' => $version->version,
                'min_version' => $version->min_version,
                'force_update' => $version->force_update,
                'release_notes' => $version->release_notes,
            ]
        ]);
    }

    public function agreementStatus(Request $request)
    {
        $type = $request->query('type');
        /** @var \Illuminate\Database\Eloquent\Model $user */
        $user = Auth::user();

        $accepted = $this->agreementService->hasAccepted($user, $type);
        return response()->json(['data' => ['accepted' => $accepted]]);
    }

    public function acceptAgreement(Request $request)
    {
        $request->validate(['type' => 'required|string']);
        $type = $request->input('type');
        /** @var \Illuminate\Database\Eloquent\Model $user */
        $user = Auth::user();

        $this->agreementService->accept($user, $type);

        return response()->json(['message' => 'Agreement accepted']);
    }
}
