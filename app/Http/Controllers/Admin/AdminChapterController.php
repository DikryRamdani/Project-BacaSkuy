<?php

namespace App\Http\Controllers\Admin;

use App\Models\Manhwa;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class AdminChapterController extends Controller
{
    // Index
    public function index()
    {
        $chapters = Chapter::with('manhwa')->orderBy('created_at', 'desc')->paginate(30);
        return view('admin.chapter.index', compact('chapters'));
    }

    // Create Form
    public function create()
    {
        // Tampilkan pilihan manhwa untuk dipilih saat upload
        $manhwas = Manhwa::orderBy('title')->get();
        return view('admin.chapter.create', compact('manhwas'));
    }
    // Upload Form
    public function showUploadForm(Manhwa $manhwa)
    {
        return view('admin.chapter.upload', compact('manhwa'));
    }

    // Handle ZIP Upload
    public function handleUpload(Request $request, Manhwa $manhwa)
    {
        $request->validate([
            'chapter_number' => 'required|string|max:10',
            'title' => 'nullable|string|max:100',
            'chapter_zip' => 'required|file|mimes:zip|max:512000', // max 500MB
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        // STEP 1 slug unik
        $baseSlug = Str::slug($manhwa->title . '-' . $request->chapter_number);
        $slug = $baseSlug;
        $counter = 1;
        while (Chapter::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $chapter = $manhwa->chapters()->create([
            'chapter_number' => $request->chapter_number,
            'title' => $request->title,
            'slug' => $slug,
        ]);

        try {
            // STEP 2 simpan ZIP tmp
            $zipFile = $request->file('chapter_zip');
            $tmpZipPath = $zipFile->storeAs('tmp_uploads', 'chapter_' . $chapter->id . '.zip');
            $fullZipPath = Storage::path($tmpZipPath);

            // STEP 3 buka ZIP
            $zip = new \ZipArchive();
            if ($zip->open($fullZipPath) !== true) {
                throw new \Exception('Gagal membuka file ZIP.');
            }

            // STEP 4 buat folder output
            $outputFolder = 'chapters/' . $slug;
            Storage::disk('public')->makeDirectory($outputFolder);
            $outputFullPath = Storage::disk('public')->path($outputFolder);

            // STEP 5 kumpulkan file gambar
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $extractedImages = [];

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $fileInfo = pathinfo($filename);
                
                // skip folder/hidden
                if (empty($fileInfo['extension']) || strpos($filename, '__MACOSX') !== false || strpos($filename, '.') === 0) {
                    continue;
                }

                // hanya gambar
                $ext = strtolower($fileInfo['extension']);
                if (in_array($ext, $imageExtensions)) {
                    $extractedImages[] = [
                        'index' => $i,
                        'name' => $filename,
                        'basename' => $fileInfo['basename']
                    ];
                }
            }

            // STEP 6 sort natural
            usort($extractedImages, function($a, $b) {
                return strnatcasecmp($a['basename'], $b['basename']);
            });

            // STEP 7 extract + rename berurutan
            $pageNumber = 1;
            foreach ($extractedImages as $img) {
                $content = $zip->getFromIndex($img['index']);
                if ($content === false) {
                    continue;
                }

                // tentukan extension
                $originalExt = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
                $newFilename = 'page_' . $pageNumber . '.' . $originalExt;
                $destPath = $outputFullPath . DIRECTORY_SEPARATOR . $newFilename;

                // simpan file
                file_put_contents($destPath, $content);

                // create Page record
                Page::create([
                    'chapter_id' => $chapter->id,
                    'page_number' => $pageNumber,
                    'image_url' => 'storage/chapters/' . $slug . '/' . $newFilename,
                ]);

                $pageNumber++;
            }

            $zip->close();

            // STEP 8 hapus ZIP tmp
            Storage::delete($tmpZipPath);

            // STEP 9 simpan thumbnail (opsional)
            if ($request->hasFile('thumbnail')) {
                $thumbPath = $request->file('thumbnail')->storeAs(
                    'chapter_thumbs',
                    'chapter_' . $chapter->id . '.' . $request->file('thumbnail')->getClientOriginalExtension(),
                    'public'
                );
                if (Schema::hasColumn('chapters', 'thumbnail')) {
                    $chapter->thumbnail = $thumbPath;
                    $chapter->save();
                }
            }

            Log::info('Chapter uploaded successfully', [
                'chapter_id' => $chapter->id,
                'slug' => $slug,
                'total_pages' => $pageNumber - 1
            ]);

            return back()->with('success', 'Chapter berhasil di-upload! Total ' . ($pageNumber - 1) . ' halaman.');

        } catch (\Exception $e) {
            // rollback jika gagal
            Log::error('Chapter upload failed', [
                'chapter_id' => $chapter->id,
                'error' => $e->getMessage()
            ]);
            
            $chapter->delete();
            
            return back()->with('error', 'Gagal upload chapter: ' . $e->getMessage());
        }
    }

    // Store wrapper
    public function store(Request $request)
    {
        // validasi & delegasikan ke handleUpload
        $request->validate([
            'manhwa_id' => 'required|exists:manhwas,id',
        ]);
        $manhwa = Manhwa::findOrFail($request->manhwa_id);
        return $this->handleUpload($request, $manhwa);
    }

    // Destroy
    public function destroy(Chapter $chapter)
    {
        // hapus folder gambar
        $chapterFolder = 'chapters/' . $chapter->slug;
        if (Storage::disk('public')->exists($chapterFolder)) {
            Storage::disk('public')->deleteDirectory($chapterFolder);
        }

        // hapus thumbnail
        if (!empty($chapter->thumbnail) && Storage::disk('public')->exists($chapter->thumbnail)) {
            Storage::disk('public')->delete($chapter->thumbnail);
        }

        // hapus pages
        $chapter->pages()->delete();
        
        // hapus chapter record
        $chapter->delete();
        
        return redirect()->route('admin.chapter.index')->with('success', 'Chapter berhasil dihapus');
    }

    // Show
    public function show(Chapter $chapter)
    {
        $chapter->load('manhwa');
        return view('admin.chapter.show', compact('chapter'));
    }

    // Edit Form
    public function edit(Chapter $chapter)
    {
        $manhwas = Manhwa::orderBy('title')->get();
        return view('admin.chapter.edit', compact('chapter', 'manhwas'));
    }

    // Update
    public function update(Request $request, Chapter $chapter)
    {
        $request->validate([
            'manhwa_id' => 'required|exists:manhwas,id',
            'chapter_number' => 'required|string|max:10',
            'title' => 'nullable|string|max:200',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $chapter->manhwa_id = $request->manhwa_id;
        $chapter->chapter_number = $request->chapter_number;
        $chapter->title = $request->title;

        // thumbnail replacement
        if ($request->hasFile('thumbnail')) {
            // delete old
            if (!empty($chapter->thumbnail) && Storage::disk('public')->exists($chapter->thumbnail)) {
                Storage::disk('public')->delete($chapter->thumbnail);
            }
            $thumbPath = $request->file('thumbnail')->storeAs(
                'chapter_thumbs',
                'chapter_' . $chapter->id . '.' . $request->file('thumbnail')->getClientOriginalExtension(),
                'public'
            );
            if (Schema::hasColumn('chapters', 'thumbnail')) {
                $chapter->thumbnail = $thumbPath;
            }
        }

        $chapter->save();

        return redirect()->route('admin.chapter.index')->with('success', 'Chapter berhasil diperbarui');
    }

    // Bulk Destroy
    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:chapters,id'
        ]);

        $chapters = Chapter::whereIn('id', $data['ids'])->get();
        foreach ($chapters as $chapter) {
            // hapus folder gambar
            $chapterFolder = 'chapters/' . $chapter->slug;
            if (Storage::disk('public')->exists($chapterFolder)) {
                Storage::disk('public')->deleteDirectory($chapterFolder);
            }
            
            // hapus thumbnail
            if (!empty($chapter->thumbnail) && Storage::disk('public')->exists($chapter->thumbnail)) {
                Storage::disk('public')->delete($chapter->thumbnail);
            }
            
            // hapus pages + chapter
            $chapter->pages()->delete();
            $chapter->delete();
        }

        return redirect()->route('admin.chapter.index')->with('success', 'Chapter terpilih berhasil dihapus.');
    }

}