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
        $cicid = $request->input('cicid'); // Or from auth
        $name = $request->input('name', 'default');

        if (!$cicid) {
            return response()->json(['message' => 'CICID required'], 400);
        }

        $exists = UserAgreement::where(['cicid' => $cicid, 'name' => $name])->exists();

        return response()->json(['accepted' => $exists]);
    }

    /**
     * Create user agreement record.
     * @api POST /api/user-agreements/accept
     */
    public function accept(Request $request)
    {
        $cicid = $request->input('cicid'); // Or from auth
        $name = $request->input('name', 'default');

        if (!$cicid) {
            return response()->json(['message' => 'CICID required'], 400);
        }

        $agreement = UserAgreement::create(['cicid' => $cicid, 'name' => $name]);

        return response()->json($agreement);
    }
}
