@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold mb-4">Edit Manhwa — {{ $manhwa->title }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.manhwa.update', $manhwa) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded shadow-sm">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Title</label>
                    <input type="text" name="title" value="{{ old('title', $manhwa->title) }}"
                        class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium">Slug (optional)</label>
                    <input type="text" name="slug" value="{{ old('slug', $manhwa->slug) }}"
                        class="w-full border rounded p-2">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description" rows="4" class="w-full border rounded p-2">{{ old('description', $manhwa->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium">Author</label>
                    <input type="text" name="author" value="{{ old('author', $manhwa->author) }}"
                        class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Artist</label>
                    <input type="text" name="artist" value="{{ old('artist', $manhwa->artist) }}"
                        class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="">-- Pilih Status --</option>
                        <option value="ongoing" {{ old('status', $manhwa->status) == 'ongoing' ? 'selected' : '' }}>Ongoing
                        </option>
                        <option value="completed" {{ old('status', $manhwa->status) == 'completed' ? 'selected' : '' }}>
                            Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Format</label>
                    <select name="format" class="w-full border rounded p-2">
                        <option value="">-- Pilih Format --</option>
                        <option value="manga" {{ old('format', $manhwa->format) == 'manga' ? 'selected' : '' }}>Manga
                        </option>
                        <option value="manhwa" {{ old('format', $manhwa->format) == 'manhwa' ? 'selected' : '' }}>Manhwa
                        </option>
                        <option value="manhua" {{ old('format', $manhwa->format) == 'manhua' ? 'selected' : '' }}>Manhua
                        </option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Genres (Pilih beberapa)</label>
                    <div
                        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 border rounded p-3 bg-gray-50 max-h-64 overflow-y-auto">
                        @php
                            $selectedGenreIds = old('genre_ids', $manhwa->genres->pluck('id')->toArray());
                        @endphp
                        @forelse($genres as $genre)
                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 p-2 rounded">
                                <input type="checkbox" name="genre_ids[]" value="{{ $genre->id }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ in_array($genre->id, $selectedGenreIds) ? 'checked' : '' }}>
                                <span class="text-sm">{{ $genre->name }}</span>
                            </label>
                        @empty
                            <p class="text-gray-500 text-sm col-span-full">No genres available.</p>
                        @endforelse
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Cover Image (leave empty to keep)</label>
                    <input type="file" name="cover" class="w-full">
                    @if ($manhwa->cover_image)
                        <img src="{{ $manhwa->cover_url }}" alt="cover" class="mt-2 w-40 h-40 object-cover">
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                <a href="{{ route('admin.manhwa.index') }}" class="ml-2 text-gray-600">Batal</a>
            </div>
        </form>
    </div>
@endsection
