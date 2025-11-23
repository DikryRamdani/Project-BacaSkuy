<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.genres.index', compact('genres'));
    }

    // Create Form
    public function create()
    {
        return view('admin.genres.create');
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:genres,name',
        ]);

        Genre::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // redirect resource
        return redirect()->route('admin.genre.index')->with('success', 'Genre berhasil ditambahkan.');
    }

    // Edit Form
    public function edit(Genre $genre)
    {
        // route model binding
        return view('admin.genres.edit', compact('genre'));
    }

    // Update
    public function update(Request $request, Genre $genre)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:genres,name,' . $genre->id,
        ]);

        $genre->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.genre.index')->with('success', 'Genre berhasil diperbarui.');
    }

    // Destroy
    public function destroy(Genre $genre)
    {
        // detach pivot (optional)

        $genre->delete();

        return redirect()->route('admin.genre.index')->with('success', 'Genre berhasil dihapus.');
    }
}
