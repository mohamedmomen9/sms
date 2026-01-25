<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Models\AppVersion;

class AppVersionController extends Controller
{
    /**
     * Get latest app version for a device.
     * @api GET /api/app-versions/latest
     */
    public function latest(Request $request)
    {
        $device = $request->input('device'); // ios or android

        if (!$device) {
            return response()->json(['message' => 'Device type required'], 400);
        }

        $version = AppVersion::where('platform', $device)->value('version');

        return response()->json(['version' => $version]);
    }
}
