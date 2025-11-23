<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Manhwa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Toggle
    public function toggle(Manhwa $manhwa)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('manhwa_id', $manhwa->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
            $message = 'Dihapus dari favorit';
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'manhwa_id' => $manhwa->id,
            ]);
            $isFavorited = true;
            $message = 'Ditambahkan ke favorit';
        }

        return response()->json([
            'success' => true,
            'isFavorited' => $isFavorited,
            'message' => $message,
        ]);
    }

    // Check
    public function check(Manhwa $manhwa)
    {
        if (!Auth::check()) {
            return response()->json(['isFavorited' => false]);
        }

        $isFavorited = Favorite::where('user_id', Auth::id())
            ->where('manhwa_id', $manhwa->id)
            ->exists();

        return response()->json(['isFavorited' => $isFavorited]);
    }
}
