<?php

namespace App\Http\Controllers;

use App\Models\Manhwa;
use App\Models\Chapter;
use App\Models\FeaturedManhwa;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class ManhwaController extends Controller
{
    // Home
    public function home(Request $request)
    {
        // basic query + search
        $perPage = 20;
        $q = trim($request->get('q', ''));
        $query = Manhwa::query();
        if ($q !== '') {
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                   ->orWhere('author', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%")
                   ->orWhereHas('genres', function ($gq) use ($q) {
                       $gq->where('name', 'like', "%{$q}%");
                   });
            });
        }
        $manhwas = $query->orderBy('updated_at', 'desc')->paginate($perPage)->withQueryString();
        // featured banner (no search)
        $featuredManhwas = collect();
        if ($q === '') {
            $featuredManhwas = FeaturedManhwa::with('manhwa.genres')
                ->active()
                ->ordered()
                ->get()
                ->pluck('manhwa');
        }
        // continue reading
        $continueReading = collect();
        if (Auth::check() && $q === '') {
            $continueReading = ReadingHistory::where('user_id', Auth::id())
                ->with(['manhwa.chapters', 'chapter'])
                ->orderBy('last_read_at', 'desc')
                ->take(12)
                ->get();
        }
        // recommendations (top rated)
        $recommendations = collect();
        if ($q === '') {
            $recommendations = Manhwa::withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->having('ratings_count', '>', 0)
                ->orderByDesc('ratings_avg_rating')
                ->take(6)
                ->get();
        }
        
        return view('public.home', compact('manhwas', 'q', 'featuredManhwas', 'continueReading', 'recommendations'));
    }

    // Detail
    public function detail($slug)
    {
        // find manhwa + chapters + comments
        $manhwa = Manhwa::where('slug', $slug)
                        ->with(['chapters', 'approvedComments'])
                        ->firstOrFail(); // Error 404 jika tidak ketemu

        return view('public.detail', compact('manhwa'));
    }

    // Reader
    public function reader($slug, $chapter_slug)
    {
        // find chapter + pages + comments
        $chapter = Chapter::where('slug', $chapter_slug)
                        ->with(['pages', 'manhwa.approvedComments'])
                        ->firstOrFail();
        
        // track history
        if (Auth::check()) {
            ReadingHistory::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'manhwa_id' => $chapter->manhwa_id,
                ],
                [
                    'chapter_id' => $chapter->id,
                    'last_read_at' => now(),
                ]
            );
        }
        
        // optional prev/next (not loaded here)

        return view('public.reader', compact('chapter'));
    }

    // Suggest (AJAX) buat searchbar
    public function suggest(Request $request)
    {
        $q = trim($request->get('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $items = Manhwa::where('title', 'like', "%{$q}%")
            ->orWhere('author', 'like', "%{$q}%")
            ->orderBy('title')
            ->limit(8)
            ->get();

        $data = $items->map(function ($m) {
            return [
                'title' => $m->title,
                'author' => $m->author,
                'slug' => $m->slug,
                'cover' => $m->cover_url,
            ];
        });

        return response()->json($data);
    }

    // Library
    public function library(Request $request)
    {
        if (!Auth::check()) {
            // Guest: show empty favorites + flag
            $favorites = collect();
            $manhwas = collect();
            $guest = true;
            return view('public.library', compact('manhwas', 'favorites', 'guest'));
        }

        $perPage = 24;
        $favorites = Auth::user()->favorites()
            ->with(['manhwa.genres'])
            ->latest()
            ->paginate($perPage);

        $manhwas = $favorites->map(fn($favorite) => $favorite->manhwa);
        $guest = false;
        return view('public.library', compact('manhwas', 'favorites', 'guest'));
    }
    
}