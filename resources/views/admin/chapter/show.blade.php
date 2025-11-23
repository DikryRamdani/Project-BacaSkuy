@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Detail Chapter</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.chapter.edit', $chapter->id) }}"
                    class="px-3 py-2 bg-indigo-600 text-white rounded">Edit</a>
                <a href="{{ route('admin.chapter.index') }}" class="px-3 py-2 bg-gray-200 rounded">Kembali</a>
            </div>
        </div>

        <div class="bg-white rounded shadow-sm p-6">
            <div class="flex gap-6">
                <div class="w-24 h-32 bg-gray-100 rounded overflow-hidden">
                    @php $thumb = $chapter->thumbnail ? asset('storage/' . $chapter->thumbnail) : ($chapter->manhwa->cover_url ?? 'https://via.placeholder.com/96x128?text=Cover'); @endphp
                    <img src="{{ $thumb }}" alt="thumb" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-semibold">{{ $chapter->title ?? 'Chapter ' . $chapter->chapter_number }}</h2>
                    <div class="text-sm text-gray-600">Manhwa: {{ $chapter->manhwa->title ?? '-' }}</div>
                    <div class="text-sm text-gray-600">Nomor: {{ $chapter->chapter_number }}</div>
                    <div class="text-sm text-gray-600">Tanggal:
                        {{ $chapter->created_at ? $chapter->created_at->format('Y-m-d H:i') : '' }}</div>
                    <div class="text-sm text-gray-600">Halaman: {{ $chapter->pages()->count() }}</div>
                </div>
            </div>

            <hr class="my-4">
            <div>
                <h3 class="font-medium">Metadata</h3>
                <pre class="text-xs bg-gray-50 rounded p-2 mt-2">Slug: {{ $chapter->slug }}
ID: {{ $chapter->id }}</pre>
            </div>
        </div>
    </div>
@endsection
