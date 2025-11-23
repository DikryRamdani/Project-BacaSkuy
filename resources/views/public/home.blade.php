@extends('layouts.app')

@section('content')
    @php $q = $q ?? ''; @endphp

    {{-- Hero Banner Section - Featured manhwa carousel --}}
    @if (empty($q) && $featuredManhwas->count() > 0)
        @php
            $slides = $featuredManhwas
                ->map(function ($manhwa) {
                    return [
                        'title' => $manhwa->title,
                        'rating' => number_format($manhwa->averageRating(), 1),
                        'genre' => $manhwa->genres->first()->name ?? 'Unknown',
                        'description' => $manhwa->description ?? 'Deskripsi belum tersedia',
                        'coverImage' => $manhwa->cover_url ?? 'https://via.placeholder.com/800x400',
                    ];
                })
                ->toArray();
        @endphp
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <x-hero-banner :slides="$slides" :totalSlides="$featuredManhwas->count()" />
        </div>
    @endif

    {{-- Continue Reading Section --}}
    @if (empty($q) && $continueReading->count() > 0)
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Lanjutkan Membaca</h2>
                    <a href="{{ route('library.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                        Lihat Semua
                    </a>
                </div>

                <div class="overflow-x-auto scrollbar-hide -mx-2 px-2">
                    <div class="flex gap-4" style="min-width: min-content;">
                        @foreach ($continueReading as $history)
                            @php
                                $manhwa = $history->manhwa;
                                $currentChapter = $history->chapter;
                                $totalChapters = $manhwa->chapters->count();
                                $currentChapterNumber = $currentChapter->chapter_number ?? 0;
                                $progress = $totalChapters > 0 ? ($currentChapterNumber / $totalChapters) * 100 : 0;
                            @endphp
                            <a href="{{ route('chapter.reader', ['slug' => $manhwa->slug, 'chapter_slug' => $currentChapter->slug]) }}"
                                class="group block shrink-0" style="width: 160px;">
                                <div class="relative rounded-lg overflow-hidden bg-gray-100" style="aspect-ratio:3/4;">
                                    <img src="{{ $manhwa->cover_url ?? 'https://via.placeholder.com/300x400' }}"
                                        alt="{{ $manhwa->title }}" class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="absolute bottom-0 left-0 right-0 p-3">
                                            <div class="flex items-center gap-1 text-white text-xs mb-1">
                                                <i class="bi bi-play-circle-fill"></i>
                                                <span>Lanjutkan</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Progress Bar --}}
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-700">
                                        <div class="h-full bg-indigo-600 transition-all"
                                            style="width: {{ $progress }}%">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h3 class="font-medium text-sm line-clamp-1">{{ $manhwa->title }}</h3>
                                    <p class="text-xs text-gray-500">Chapter
                                        {{ $currentChapterNumber }}</p>
                                    <div class="flex items-center gap-1 text-xs text-gray-400 mt-1">
                                        <i class="bi bi-clock-history"></i>
                                        <span>{{ $history->last_read_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Rekomendasi Section - Hanya tampil kalau tidak ada pencarian --}}
    @if (empty($q) && $recommendations->count() > 0)
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xl font-semibold">Rekomendasi untuk Anda</h2>
                    <span class="text-xs text-gray-500">Berdasarkan Rating Tertinggi</span>
                </div>

                <x-manhwa-card :manhwa="$recommendations" />
            </div>
        </div>
    @endif

    {{-- Main Content - Grid Semua Manhwa --}}
    <main class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-12">
        {{-- Header dengan jumlah total --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Semua Seri</h2>
            <div class="text-sm text-gray-600">Menampilkan {{ $manhwas->total() }} seri</div>
        </div>

        @if ($manhwas->count())
            <x-manhwa-card :manhwa="$manhwas" />
        @endif
    </main>
@endsection
