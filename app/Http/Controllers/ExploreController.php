<?php

namespace App\Http\Controllers;

use App\Models\Manhwa;
use App\Models\Genre;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    // Index (explore) list + filters + sorting
    public function index(Request $request)
    {
        $perPage = 24; // page size
        $q = trim($request->get('q', ''));
        $sortBy = $request->get('sort', 'title');
        $genres = $request->get('genres', []);
        $status = $request->get('status', '');
        $year = $request->get('year', '');

        // base query with common aggregates
        $query = Manhwa::query()
            ->withCount('chapters')
            ->withAvg('ratings', 'rating')
            ->withCount('favorites');

        // search filter
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

        // genre filter
        if (!empty($genres) && is_array($genres)) {
            $query->whereHas('genres', function ($gq) use ($genres) {
                $gq->whereIn('genres.id', $genres);
            });
        }

        // status filter
        if ($status !== '' && in_array($status, ['Ongoing', 'Completed', 'Hiatus'])) {
            $query->where('status', $status);
        }

        // year filter
        if ($year !== '' && is_numeric($year)) {
            $query->whereYear('created_at', $year);
        }

        // sorting
        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'rating':
                // Use withAvg eager loaded column
                $query->orderByDesc('ratings_avg_rating');
                break;
            case 'favorites':
                // Use withCount favorites
                $query->orderByDesc('favorites_count');
                break;
            case 'latest':
                $query->orderBy('updated_at', 'desc');
                break;
            default:
                $query->orderBy('title', 'asc');
                break;
        }

        // paginate
        $manhwas = $query->paginate($perPage)->withQueryString();

        // genres list
        $allGenres = Genre::orderBy('name')->get();

        // years list
        $availableYears = Manhwa::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('public.explore', compact('manhwas', 'q', 'sortBy', 'genres', 'status', 'year', 'allGenres', 'availableYears'));
    }
}
