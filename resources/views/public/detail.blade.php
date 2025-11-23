@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8" data-manhwa-id="{{ $manhwa->id }}"
        data-authenticated="{{ Auth::check() ? 'true' : 'false' }}">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/3">
                    <div class="rounded-md overflow-hidden bg-gray-100" style="aspect-ratio:3/4;">
                        @if ($manhwa->cover_image)
                            <img src="{{ $manhwa->cover_url }}" alt="{{ $manhwa->title }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://via.placeholder.com/300x400?text=No+Cover" alt="No cover"
                                class="w-full h-full object-cover">
                        @endif
                    </div>
                </div>

                <div class="flex-1">
                    <h1 class="text-2xl font-semibold">{{ $manhwa->title }}</h1>
                    <div class="text-sm text-gray-600 mt-1">
                        {{ $manhwa->author ? 'Author: ' . $manhwa->author : '' }}
                        @if ($manhwa->artist)
                            &middot; {{ 'Artist: ' . $manhwa->artist }}
                        @endif
                    </div>

                    <div class="mt-3 space-y-2 text-sm">
                        @if ($manhwa->status)
                            <div><strong>Status:</strong> {{ $manhwa->status }}</div>
                        @endif
                        @if ($manhwa->format)
                            <div><strong>Format:</strong> {{ $manhwa->format }}</div>
                        @endif
                        <div>
                            <strong>Genre:</strong>
                            @if ($manhwa->genres && $manhwa->genres->count())
                                @foreach ($manhwa->genres as $g)
                                    <span
                                        class="inline-block text-xs bg-gray-100 px-2 py-0.5 rounded mr-1">{{ $g->name }}</span>
                                @endforeach
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 text-gray-700">
                        {!! nl2br(e($manhwa->description ?? 'Deskripsi belum tersedia')) !!}
                    </div>

                    {{-- Rating & Favorite Section --}}
                    <div class="mt-6 flex flex-wrap items-center gap-4">
                        {{-- Average Rating Display --}}
                        <div class="flex items-center gap-2">
                            <div class="flex items-center text-yellow-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill text-xl"></i>
                                @endfor
                            </div>
                            <span class="text-lg font-semibold"
                                id="averageRating">{{ number_format($manhwa->averageRating(), 1) }}</span>
                            <span class="text-sm text-gray-500">(<span
                                    id="totalRatings">{{ $manhwa->ratings()->count() }}</span> rating)</span>
                        </div>

                        {{-- User Rating (if logged in) --}}
                        @auth
                            <button onclick="openRatingModal()" class="text-sm text-blue-500 hover:text-blue-700">
                                <i class="bi bi-star"></i> Beri Rating
                            </button>
                        @endauth

                        {{-- Favorite Button --}}
                        @auth
                            <button id="favoriteBtn" onclick="toggleFavorite()"
                                class="flex items-center justify-center w-10 h-10 rounded-lg border transition-all">
                                <i id="favoriteIcon" class="bi text-xl"></i>
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                                class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">
                                <i class="bi bi-heart text-xl"></i>
                                <span>Login untuk Favorit</span>
                            </a>
                        @endauth
                    </div>

                    {{-- Smart Read Button --}}
                    @if ($manhwa->chapters && $manhwa->chapters->count() > 0)
                        @php
                            $firstChapter = $manhwa->chapters->sortBy('chapter_number')->first();
                            $readingHistory = Auth::check() ? $manhwa->getReadingHistoryFor(Auth::id()) : null;
                            $targetChapter = $readingHistory ? $readingHistory->chapter : $firstChapter;
                            $buttonText = $readingHistory
                                ? 'Lanjutkan Baca Chapter ' . ($targetChapter->chapter_number ?? '')
                                : 'Mulai Baca';
                        @endphp
                        <div class="mt-4">
                            <a href="{{ route('chapter.reader', ['slug' => $manhwa->slug, 'chapter_slug' => $targetChapter->slug]) }}"
                                class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-all">
                                <i class="bi bi-book"></i> {{ $buttonText }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <hr class="my-6">

            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold">Daftar Chapter</h2>
                    @if ($manhwa->chapters && $manhwa->chapters->count() > 10)
                        <span class="text-sm text-gray-500">{{ $manhwa->chapters->count() }} chapter</span>
                    @endif
                </div>
                @if ($manhwa->chapters && $manhwa->chapters->count())
                    @php
                        // initial number of chapters shown before expand
                        $initialVisible = 5; // ubah ke 5 kalau mau lebih sedikit
                        $sortedChapters = $manhwa->chapters->sortByDesc('chapter_number');
                        $visibleChapters = $sortedChapters->take($initialVisible);
                        $hasMore = $sortedChapters->count() > $initialVisible;
                    @endphp

                    <ul class="space-y-2" id="chapterList">
                        @foreach ($visibleChapters as $chapter)
                            @php
                                $thumb = $chapter->thumbnail
                                    ? asset('storage/' . $chapter->thumbnail)
                                    : $manhwa->cover_url ?? 'https://via.placeholder.com/96x128?text=Cover';
                                $chapNum = $chapter->chapter_number ?? '';
                                $title = $chapter->title ? $chapter->title : 'Chapter ' . $chapNum;
                            @endphp
                            <li class="flex items-center justify-between bg-gray-50 p-3 rounded chapter-item">
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-20 bg-gray-100 rounded overflow-hidden shrink-0">
                                        <img src="{{ $thumb }}" alt="{{ $title }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <a href="{{ route('chapter.reader', ['slug' => $manhwa->slug, 'chapter_slug' => $chapter->slug]) }}"
                                            class="font-medium hover:underline">{{ $title }}</a>
                                        <div class="text-xs text-gray-500">
                                            Chapter: {{ $chapNum }} &middot;
                                            {{ $chapter->created_at ? $chapter->created_at->diffForHumans() : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 text-right">
                                    <div>{{ $chapter->pages()->count() }} halaman</div>
                                </div>
                            </li>
                        @endforeach

                        @if ($hasMore)
                            @foreach ($sortedChapters->skip($initialVisible) as $chapter)
                                @php
                                    $thumb = $chapter->thumbnail
                                        ? asset('storage/' . $chapter->thumbnail)
                                        : $manhwa->cover_url ?? 'https://via.placeholder.com/96x128?text=Cover';
                                    $chapNum = $chapter->chapter_number ?? '';
                                    $title = $chapter->title ? $chapter->title : 'Chapter ' . $chapNum;
                                @endphp
                                <li
                                    class="hidden items-center justify-between bg-gray-50 p-3 rounded chapter-item chapter-hidden">
                                    <div class="flex items-center gap-3">
                                        <div class="w-16 h-20 bg-gray-100 rounded overflow-hidden shrink-0">
                                            <img src="{{ $thumb }}" alt="{{ $title }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <a href="{{ route('chapter.reader', ['slug' => $manhwa->slug, 'chapter_slug' => $chapter->slug]) }}"
                                                class="font-medium hover:underline">{{ $title }}</a>
                                            <div class="text-xs text-gray-500">
                                                Chapter: {{ $chapNum }} &middot;
                                                {{ $chapter->created_at ? $chapter->created_at->diffForHumans() : '' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500 text-right">
                                        <div>{{ $chapter->pages()->count() }} halaman</div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>

                    @if ($hasMore)
                        <div class="mt-4 text-center">
                            <button onclick="toggleAllChapters()" id="toggleChaptersBtn"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="bi bi-list-ul"></i>
                                <span id="toggleBtnText">Lihat Semua Chapter ({{ $sortedChapters->count() }})</span>
                                <i class="bi bi-chevron-down" id="toggleBtnIcon"></i>
                            </button>
                        </div>

                        <script>
                            // Set total chapters for detail-chapters.js
                            window.totalChapters = {{ $sortedChapters->count() }};
                        </script>
                    @endif
                @else
                    <div class="text-gray-500">Belum ada chapter untuk seri ini.</div>
                @endif
            </div>

            <hr class="my-6">

            <x-comment-field :manhwa="$manhwa" />
        </div>
    </div>

    {{-- Rating Modal --}}
    @auth
        <div id="ratingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Beri Rating</h3>
                    <button onclick="closeRatingModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 mb-4">Berikan rating untuk {{ $manhwa->title }}</p>
                    <div class="flex justify-center gap-2 mb-6" id="ratingStars">
                        @for ($i = 1; $i <= 10; $i++)
                            <button onclick="selectRating({{ $i }})"
                                class="rating-star text-3xl text-gray-300 hover:text-yellow-500 transition-colors"
                                data-rating="{{ $i }}">
                                <i class="bi bi-star-fill"></i>
                            </button>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Rating: <span id="selectedRating" class="font-bold">0</span>/10</p>
                    <button onclick="submitRating()" id="submitRatingBtn"
                        class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:bg-gray-300 disabled:cursor-not-allowed"
                        disabled>
                        Submit Rating
                    </button>
                </div>
            </div>
        </div>
    @endauth
@endsection

@push('scripts')
    <script src="{{ asset('js/public/detailChapters.js') }}"></script>
    <script src="{{ asset('js/public/detailManhwa.js') }}"></script>
@endpush
