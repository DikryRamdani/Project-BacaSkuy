<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedManhwa;
use App\Models\Manhwa;
use Illuminate\Http\Request;

class FeaturedManhwaController extends Controller
{
    // Index
    public function index()
    {
        $featuredManhwas = FeaturedManhwa::with('manhwa')->ordered()->get();
        return view('admin.featured-manhwa.index', compact('featuredManhwas'));
    }

    // Create Form
    public function create()
    {
        // non-featured manhwas
        $manhwas = Manhwa::whereDoesntHave('featured')->orderBy('title')->get();
        $maxOrder = FeaturedManhwa::max('order') ?? 0;
        
        return view('admin.featured-manhwa.create', compact('manhwas', 'maxOrder'));
    }

    // Store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'manhwa_id' => 'required|exists:manhwas,id|unique:featured_manhwa,manhwa_id',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        FeaturedManhwa::create($validated);

        return redirect()->route('admin.featured-manhwa.index')
            ->with('success', 'Manhwa berhasil ditambahkan ke hero banner!');
    }

    // Update
    public function update(Request $request, FeaturedManhwa $featuredManhwa)
    {
        $validated = $request->validate([
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $featuredManhwa->update($validated);

        return back()->with('success', 'Featured manhwa berhasil diupdate!');
    }

    // Destroy
    public function destroy(FeaturedManhwa $featuredManhwa)
    {
        $featuredManhwa->delete();

        return back()->with('success', 'Manhwa berhasil dihapus dari hero banner!');
    }

    // Toggle active
    public function toggleActive(FeaturedManhwa $featuredManhwa)
    {
        $featuredManhwa->update([
            'is_active' => !$featuredManhwa->is_active
        ]);

        return back()->with('success', 'Status berhasil diubah!');
    }
}
