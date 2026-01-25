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
        $campus = $request->input('campus', 'All');

        $offers = Offer::where(function ($q) use ($campus) {
            $q->where('campus', $campus)->orWhere('campus', 'All');
        })->get();

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

        // Implementation from DashboardEloquentQueries uses create, but let's see if we can make it smarter
        // If it's a toggle, we should check if it exists.
        // However, I will strictly follow the provided "logic" which just creates a log entry for now
        // to match the "DashboardEloquentQueries" file provided.
        // Actually, let's implement true toggle for better UX, or stick to the code?
        // The user said "missing apis ... implemented form here". So I should copy the implementation.

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
