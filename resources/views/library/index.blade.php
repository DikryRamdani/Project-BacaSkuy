@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-12">
        <h1 class="text-2xl font-bold mb-6">Perpustakaan Saya</h1>

        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex gap-4">
                <a href="{{ route('library.index', ['tab' => 'favorites']) }}"
                    class="px-4 py-2 border-b-2 {{ $tab === 'favorites' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                    <i class="bi bi-heart-fill"></i> Favorit ({{ $favorites->count() }})
                </a>
                <a href="{{ route('library.index', ['tab' => 'history']) }}"
                    class="px-4 py-2 border-b-2 {{ $tab === 'history' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                    <i class="bi bi-clock-history"></i> Riwayat Baca ({{ $readingHistory->count() }})
                </a>
            </nav>
        </div>

        {{-- Favorites Tab --}}
        @if ($tab === 'favorites')
            @if ($favorites->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($favorites as $manhwa)
                        <a href="{{ route('manhwa.show', $manhwa->slug) }}"
                            class="block bg-white rounded-md overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                            <div style="aspect-ratio:3/4;" class="bg-gray-200">
                                <img src="{{ $manhwa->cover_url }}" alt="{{ $manhwa->title }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="p-2 text-sm">
                                <div class="font-medium">{{ \Illuminate\Support\Str::limit($manhwa->title, 40) }}</div>
                                <div class="text-xs text-gray-500">{{ $manhwa->author ?? 'Unknown' }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="bi bi-heart text-4xl text-gray-300"></i>
                    <p class="text-gray-500 mt-4">Belum ada manhwa favorit</p>
                    <a href="{{ route('home') }}" class="text-blue-500 hover:underline mt-2 inline-block">
                        Jelajahi Manhwa
                    </a>
                </div>
            @endif
        @endif

        {{-- Reading History Tab --}}
        @if ($tab === 'history')
            @if ($readingHistory->count() > 0)
                <div class="space-y-4">
                    @foreach ($readingHistory as $history)
                        <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition-all flex gap-4">
                            <a href="{{ route('manhwa.show', $history->manhwa->slug) }}">
                                <img src="{{ $history->manhwa->cover_url }}" alt="{{ $history->manhwa->title }}"
                                    class="w-24 h-32 object-cover rounded">
                            </a>
                            <div class="flex-1">
                                <a href="{{ route('manhwa.show', $history->manhwa->slug) }}"
                                    class="font-semibold text-lg hover:text-blue-600">
                                    {{ $history->manhwa->title }}
                                </a>
                                <p class="text-sm text-gray-600 mt-1">
                                    Terakhir baca: <strong>{{ $history->chapter->title }}</strong>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $history->last_read_at->diffForHumans() }}
                                </p>
                                <a href="{{ route('chapter.reader', [$history->manhwa->slug, $history->chapter->slug]) }}"
                                    class="inline-block mt-3 bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                                    <i class="bi bi-book"></i> Lanjutkan Baca
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="bi bi-clock-history text-4xl text-gray-300"></i>
                    <p class="text-gray-500 mt-4">Belum ada riwayat bacaan</p>
                    <a href="{{ route('home') }}" class="text-blue-500 hover:underline mt-2 inline-block">
                        Mulai Baca Manhwa
                    </a>
                </div>
            @endif
        @endif
    </div>
@endsection
