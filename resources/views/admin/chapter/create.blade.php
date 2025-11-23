@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <h1 class="text-xl font-semibold mb-4">Upload Chapter (ZIP)</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded shadow-sm p-4">
            <form action="{{ route('admin.chapter.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="block text-sm font-medium">Pilih Seri (Manhwa)</label>
                    <select name="manhwa_id" class="w-full mt-1 border rounded p-2" required>
                        <option value=""></option>
                        @foreach ($manhwas as $m)
                            <option value="{{ $m->id }}">{{ $m->title }}</option>
                        @endforeach
                    </select>
                    @error('manhwa_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Nomor Chapter</label>
                    <input type="text" name="chapter_number" class="w-full mt-1 border rounded p-2" required
                        placeholder="Contoh: 1, 2.5, 100">
                    @error('chapter_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Judul Chapter (opsional)</label>
                    <input type="text" name="title" class="w-full mt-1 border rounded p-2"
                        placeholder="Contoh: The Beginning">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">File ZIP Berisi Gambar</label>
                    <input type="file" name="chapter_zip" accept="application/zip,.zip" class="w-full mt-1" required>
                    <p class="text-xs text-gray-500 mt-1">
                        <strong>Format:</strong> ZIP berisi gambar JPG/PNG/GIF/WebP.
                        Gambar akan diurutkan secara alfabetis (gunakan nama seperti page_001.jpg, page_002.jpg, dst).
                        <strong>Maks 500MB.</strong>
                    </p>
                    @error('chapter_zip')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- <div class="mb-3">
                    <label class="block text-sm font-medium">Thumbnail Chapter (opsional)</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full mt-1">
                    <p class="text-xs text-gray-500 mt-1">Rekomendasi ukuran: 96x128 atau rasio 3:4. Maks 5MB.</p>
                    @error('thumbnail')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Upload
                        Chapter</button>
                    <a href="{{ route('admin.chapter.index') }}"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
                </div>
            </form>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded p-4 mt-4">
            <h3 class="font-semibold mb-2">Tips Upload:</h3>
            <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                <li>Pastikan gambar di dalam ZIP diberi nama berurutan (contoh: 001.jpg, 002.jpg, atau page_1.png,
                    page_2.png)</li>
                <li>Gambar akan otomatis diurutkan secara natural (natural sort)</li>
                <li>Format gambar yang didukung: JPG, JPEG, PNG, GIF, WebP</li>
                <li>Ukuran maksimal ZIP: 500MB</li>
                <li>Jangan masukkan subfolder dalam ZIP — letakkan semua gambar di root ZIP</li>
            </ul>
        </div>
    </div>
@endsection
