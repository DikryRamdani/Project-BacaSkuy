<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Manhwa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    // Store / Update
    public function store(Request $request, Manhwa $manhwa)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:10',
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'manhwa_id' => $manhwa->id,
            ],
            [
                'rating' => $validated['rating'],
            ]
        );

        $averageRating = $manhwa->averageRating();

        return response()->json([
            'success' => true,
            'message' => 'Rating berhasil disimpan!',
            'averageRating' => round($averageRating, 1),
            'totalRatings' => $manhwa->ratings()->count(),
        ]);
    }

    // Get user rating
    public function getUserRating(Manhwa $manhwa)
    {
        if (!Auth::check()) {
            return response()->json(['rating' => null]);
        }

        $rating = Rating::where('user_id', Auth::id())
            ->where('manhwa_id', $manhwa->id)
            ->first();

        return response()->json([
            'rating' => $rating ? $rating->rating : null,
        ]);
    }
}
