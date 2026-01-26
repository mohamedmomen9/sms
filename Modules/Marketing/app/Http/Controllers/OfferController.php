<?php

namespace Modules\Marketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Marketing\Models\Offer;
use Modules\Marketing\Models\OfferLog;

class OfferController extends Controller
{
    /**
     * Get offers by campus.
     * @api GET /api/offers
     */
    public function index(Request $request)
    {
        $campusId = $request->campus_id;

        $offers = Offer::forCampus($campusId)->get();

        return response()->json($offers);
    }

    /**
     * Get student's favorite offers.
     * @api GET /api/offers/favorites
     */
    public function favorites(Request $request)
    {
        $studentId = $request->input('student_id');

        if (!$studentId) {
            return response()->json(['message' => 'Student ID required'], 400);
        }

        $offerLog = OfferLog::where(['cicid' => $studentId, 'is_favorite' => 1])->pluck('offer_id');
        $favOffers = Offer::whereIn('id', $offerLog)->get();

        return response()->json($favOffers);
    }

    /**
     * Toggle offer like.
     * @api POST /api/offers/{id}/like
     */
    public function toggleLike(Request $request, $id)
    {
        $studentId = $request->input('student_id');

        if (!$studentId) {
            return response()->json(['message' => 'Student ID required'], 400);
        }

        $offer = Offer::find($id);
        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }

        // Log the favorite action for this offer

        $log = OfferLog::create([
            'offer_id'    => $id,
            'cicid'       => $studentId,
            'is_favorite' => 1
        ]);

        return response()->json($log);
    }

    /**
     * Get single offer.
     * @api GET /api/offers/{id}
     */
    public function show($id)
    {
        $offer = Offer::find($id);
        if (!$offer) return response()->json(['message' => 'Not found'], 404);
        return response()->json($offer);
    }
}
