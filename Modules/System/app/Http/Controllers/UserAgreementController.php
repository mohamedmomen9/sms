<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Models\UserAgreement;

class UserAgreementController extends Controller
{
    /**
     * Check if user has accepted an agreement.
     * @api GET /api/user-agreements/status
     */
    public function status(Request $request)
    {
        $user = $request->user();


        $type = $request->input('type', 'default'); // Renamed 'name' to 'type' to match model

        $exists = UserAgreement::hasAccepted($user, $type);

        return response()->json(['accepted' => $exists]);
    }

    /**
     * Create user agreement record.
     * @api POST /api/user-agreements/accept
     */
    public function accept(Request $request)
    {
        $user = $request->user();


        $type = $request->input('type', 'default');

        $agreement = UserAgreement::accept($user, $type);

        return response()->json($agreement);
    }
}
