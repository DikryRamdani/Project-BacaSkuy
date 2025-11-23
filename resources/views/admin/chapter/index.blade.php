@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Manajemen Chapter</h1>
            <a href="{{ route('admin.chapter.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Upload Chapter
                (ZIP)</a>
        </div>

        @if ($chapters->count())
            @php
                // Group chapters by manhwa
                $groupedChapters = $chapters->groupBy('manhwa_id');
            @endphp

            <div class="space-y-6">
                @foreach ($groupedChapters as $manhwaId => $manhwaChapters)
                    @php
                        $manhwa = $manhwaChapters->first()->manhwa;
                    @endphp

                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        {{-- Manhwa Header --}}
                        <div class="bg-white text-gray-800 p-4 flex items-center gap-4 cursor-pointer hover:bg-gray-50 transition-colors toggle-chapters border-b"
                            data-manhwa-id="{{ $manhwaId }}">
                            <div class="w-16 h-20 bg-gray-100 rounded overflow-hidden shrink-0">
                                <img src="{{ $manhwa->cover_url ?? 'https://via.placeholder.com/64x80?text=Cover' }}"
                                    alt="{{ $manhwa->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-semibold">{{ $manhwa->title }}</h2>
                                <p class="text-sm text-gray-600">{{ $manhwaChapters->count() }} chapter</p>
                            </div>
                            <div class="text-2xl toggle-icon">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>

                        {{-- Chapters List --}}
                        <div class="p-4 chapters-content hidden" data-manhwa-id="{{ $manhwaId }}">
                            <div class="space-y-2">
                                @foreach ($manhwaChapters as $c)
                                    @php
                                        $thumb = $c->thumbnail
                                            ? asset('storage/' . $c->thumbnail)
                                            : $manhwa->cover_url ?? 'https://via.placeholder.com/96x128?text=Cover';
                                    @endphp

                                    <div
                                        class="flex items-center justify-between bg-gray-50 p-3 rounded hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center gap-3 flex-1">
                                            <div class="w-16 h-20 bg-gray-100 rounded overflow-hidden shrink-0">
                                                <img src="{{ $thumb }}"
                                                    alt="{{ $c->title ?? 'Chapter ' . $c->chapter_number }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="font-medium text-sm truncate">
                                                    {{ $c->title ?? 'Chapter ' . $c->chapter_number }}
                                                </h3>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Chapter: {{ $c->chapter_number }} &middot;
                                                    {{ $c->created_at ? $c->created_at->format('d M Y') : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0 ml-4">
                                            <span class="text-xs text-gray-500">{{ $c->pages()->count() }} hal</span>
                                            <a href="{{ route('admin.chapter.show', $c->id) }}"
                                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded text-xs hover:bg-blue-200">
                                                View
                                            </a>
                                            <a href="{{ route('admin.chapter.edit', $c->id) }}"
                                                class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded text-xs hover:bg-indigo-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.chapter.destroy', $c->id) }}" method="POST"
                                                class="delete-form inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-100 text-red-600 rounded text-xs hover:bg-red-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 bg-white rounded-lg shadow-sm p-4">
                {{ $chapters->links() }}
            </div>
        @else
            <div class="bg-white rounded shadow-sm p-4">
                <div class="text-gray-500">Belum ada chapter.</div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/chapterIndex.js') }}"></script>
@endpush
