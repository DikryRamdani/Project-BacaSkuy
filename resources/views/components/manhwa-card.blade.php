{{-- Manhwa Grid --}}
@if ($manhwa && $manhwa->count())

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-1">
        @foreach ($manhwa as $favorite)
            @php
                $mf = $favorite->manhwa ?? $favorite;
                $avgRating = $mf->averageRating();
                $chapterCount = $mf->chapters()->count();
                $readingHistory = $mf->getReadingHistoryFor(auth()->id());
            @endphp
            <div class="relative group">
                <a href="{{ route('manhwa.show', $mf->slug) }}"
                    class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-transparent hover:border-indigo-500">

                    {{-- Cover Image --}}
                    <div style="aspect-ratio:3/4;" class="bg-gray-200 relative overflow-hidden">
                        @if ($mf->cover_url)
                            <img src="{{ $mf->cover_url }}" alt="{{ $mf->title }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://via.placeholder.com/300x400?text=No+Cover" alt="{{ $mf->title }}"
                                class="w-full h-full object-cover">
                        @endif

                        {{-- Favorite Badge --}}
                        @auth
                            @if ($mf->isFavoritedBy(auth()->id()))
                                <div
                                    class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                    <i class="bi bi-heart-fill"></i>
                                </div>
                            @endif
                        @endauth

                        {{-- Rating Badge --}}
                        @if ($avgRating > 0)
                            <div
                                class="absolute top-2 left-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                <i class="bi bi-star-fill"></i>
                                <span>{{ number_format($avgRating, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Info Card --}}
                    <div class="p-3">
                        <div class="font-medium text-sm line-clamp-2 min-h-10">
                            {{ $mf->title }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $mf->author ?? 'Unknown' }}
                        </div>

                        {{-- Stats --}}
                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                            {{-- Chapter Count --}}
                            @if ($chapterCount >= 0)
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-book"></i>
                                    {{ $chapterCount }} Ch
                                </span>
                            @endif

                            {{-- Reading Progress --}}
                            @if ($readingHistory)
                                <span class="flex items-center gap-1 text-indigo-600">
                                    <i class="bi bi-clock-history"></i>
                                    Ch {{ $readingHistory->chapter->chapter_number ?? '?' }}
                                </span>
                                {{-- Progress Bar --}}
                                {{-- <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-700">
                                    <div class="h-full bg-indigo-600 transition-all"
                                        style="width: {{ $progress }}%">
                                    </div>
                                </div> --}}
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Pagination Links - Only show if $manhwas is paginated --}}
    @if (method_exists($manhwa, 'links'))
        <div class="mt-8">
            {{ $manhwa->links() }}
        </div>
    @endif
@else
    {{-- Empty State --}}
    <div class="py-16 text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-4">
            <i class="bi bi-heart text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">
            Belum Ada Favorit
        </h3>
        <p class="text-gray-600 mb-6">
            Mulai tambahkan manhwa favorit Anda untuk memudahkan akses nanti
        </p>
        <a href="{{ route('explore.index') }}"
            class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
            <i class="bi bi-search"></i>
            <span>Jelajahi Manhwa</span>
        </a>
    </div>
@endif
