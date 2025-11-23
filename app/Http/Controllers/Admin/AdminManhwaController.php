<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manhwa;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminManhwaController extends Controller
{
    // Index
    public function index()
    {
        $manhwas = Manhwa::orderBy('updated_at', 'desc')->paginate(15);
        return view('admin.manhwa.index', compact('manhwas'));
    }

    // Create Form
    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.manhwa.create', compact('genres'));
    }

    // Store
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'status' => 'nullable|in:ongoing,completed',
            'format' => 'nullable|in:manga,manhwa,manhua',
            'genre_ids' => 'nullable|array',
            'genre_ids.*' => 'exists:genres,id',
            'cover' => 'nullable|image|max:5120',
        ]);

        // cover upload
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            // save the path relative to the disk (no leading "storage/")
            $coverPath = $path;
        }

        // Slug generation and uniqueness
        $slug = $data['slug'] ?? Str::slug($data['title']);
        $originalSlug = $slug;
        $i = 1;
        while (Manhwa::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        $manhwa = Manhwa::create([
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'author' => $data['author'] ?? null,
            'artist' => $data['artist'] ?? null,
            'status' => $data['status'] ?? null,
            'format' => $data['format'] ?? null,
            'cover_image' => $coverPath,
        ]);

        // attach genres
        if (!empty($data['genre_ids'])) {
            $manhwa->genres()->sync($data['genre_ids']);
        }

        return redirect()->route('admin.manhwa.index')->with('success', 'Manhwa berhasil ditambahkan');
    }

    // Edit Form
    public function edit(Manhwa $manhwa)
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.manhwa.edit', compact('manhwa', 'genres'));
    }

    // Update
    public function update(Request $request, Manhwa $manhwa)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'status' => 'nullable|in:ongoing,completed',
            'format' => 'nullable|in:manga,manhwa,manhua',
            'genre_ids' => 'nullable|array',
            'genre_ids.*' => 'exists:genres,id',
            'cover' => 'nullable|image|max:5120',
        ]);

        // new cover upload
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $manhwa->setAttribute('cover_image', $path);
        }

        $manhwa->title = $data['title'];
        $manhwa->slug = $data['slug'] ? $data['slug'] : $manhwa->slug;
        $manhwa->description = $data['description'] ?? null;
        $manhwa->author = $data['author'] ?? null;
        $manhwa->artist = $data['artist'] ?? null;
        $manhwa->status = $data['status'] ?? null;
        $manhwa->format = $data['format'] ?? null;
        $manhwa->save();

        // genres sync
        if (isset($data['genre_ids'])) {
            $manhwa->genres()->sync($data['genre_ids']);
        } else {
            $manhwa->genres()->sync([]); // Clear all if none selected
        }

        return redirect()->route('admin.manhwa.index')->with('success', 'Manhwa berhasil diupdate');
    }

    // Destroy
    public function destroy(Manhwa $manhwa)
    {
        $manhwa->genres()->detach();
        $manhwa->delete();
        return redirect()->route('admin.manhwa.index')->with('success', 'Manhwa dihapus');
    }
}