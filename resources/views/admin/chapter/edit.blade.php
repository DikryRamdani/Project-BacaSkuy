@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Edit Chapter</h1>
            <a href="{{ route('admin.chapter.index') }}" class="px-3 py-2 bg-gray-200 rounded">Kembali</a>
        </div>

        <div class="bg-white rounded shadow-sm p-4">
            <form action="{{ route('admin.chapter.update', $chapter->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block text-sm font-medium">Pilih Seri (Manhwa)</label>
                    <select name="manhwa_id" class="w-full mt-1 border rounded p-2">
                        @foreach ($manhwas as $m)
                            <option value="{{ $m->id }}" @if ($m->id == $chapter->manhwa_id) selected @endif>
                                {{ $m->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Nomor Chapter</label>
                    <input type="text" name="chapter_number" value="{{ $chapter->chapter_number }}"
                        class="w-full mt-1 border rounded p-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Judul (opsional)</label>
                    <input type="text" name="title" value="{{ $chapter->title }}"
                        class="w-full mt-1 border rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Thumbnail Chapter (opsional)</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full mt-1">
                    <p class="text-xs text-gray-500 mt-1">Saat upload, thumbnail lama akan diganti. Maks 5MB.</p>
                    @if ($chapter->thumbnail)
                        <div class="mt-2 w-24 h-32 overflow-hidden rounded">
                            <img src="{{ asset('storage/' . $chapter->thumbnail) }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                </div>

                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
                    <a href="{{ route('admin.chapter.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
