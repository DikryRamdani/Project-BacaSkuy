<div id="floating-panel"
    class="fixed right-4 top-1/2 transform -translate-y-1/2 flex flex-col gap-2 z-50 opacity-0 pointer-events-none transition-opacity duration-300 ease-in-out">
    @php
        $prevChapter = $chapter->manhwa
            ->chapters()
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderBy('chapter_number', 'desc')
            ->first();
        $nextChapter = $chapter->manhwa
            ->chapters()
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number', 'asc')
            ->first();
    @endphp

    @if ($prevChapter)
        {{-- Prev Chapter --}}
        <a href="{{ route('chapter.reader', ['slug' => $chapter->manhwa->slug, 'chapter_slug' => $prevChapter->slug]) }}"
            class="px-4 py-3 bg-white shadow-lg rounded-lg hover:bg-gray-100 transition flex items-center justify-center"
            title="Previous Chapter: {{ $prevChapter->title ?: 'Chapter ' . $prevChapter->chapter_number }}">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
    @endif
    {{-- Top --}}
    <button id="top-btn" class="px-4 py-3 bg-white shadow-lg rounded-lg hover:bg-gray-100 transition"
        title="Scroll to top">
        <i class="bi bi-arrow-up text-xl"></i>
    </button>

    {{-- Fit Width --}}
    <button id="fit-btn" class="px-4 py-3 bg-white shadow-lg rounded-lg hover:bg-gray-100 transition"
        title="Fit Width">
        <i class="bi bi-arrows-angle-expand text-xl"></i>
    </button>

    {{-- Actual Size --}}
    <button id="actual-btn" class="px-4 py-3 bg-white shadow-lg rounded-lg hover:bg-gray-100 transition"
        title="Actual Size">
        <i class="bi bi-arrows-fullscreen text-xl"></i>
    </button>

    {{-- Bottom --}}
    <button id="bottom-btn" class="px-4 py-3 bg-white shadow-lg rounded-lg hover:bg-gray-100 transition"
        title="Scroll to bottom">
        <i class="bi bi-arrow-down text-xl"></i>
    </button>

    @if ($nextChapter)
        {{-- Next Chapter --}}
        <a href="{{ route('chapter.reader', ['slug' => $chapter->manhwa->slug, 'chapter_slug' => $nextChapter->slug]) }}"
            class="px-4 py-3 bg-white shadow-lg rounded-lg hover:bg-gray-100 transition flex items-center justify-center"
            title="Next Chapter: {{ $nextChapter->title ?: 'Chapter ' . $nextChapter->chapter_number }}">
            <i class="bi bi-arrow-right text-xl"></i>
        </a>
    @endif

    <a href="{{ route('manhwa.show', $chapter->manhwa->slug ?? '#') }}"
        class="px-4 py-3 bg-white shadow-lg rounded-lg hover:bg-gray-100 transition flex items-center justify-center"
        title="Back to Series">
        <i class="bi-chevron-left"></i>
    </a>
</div>

<div id="reader" class="space-y-6">
    @if ($chapter->pages && $chapter->pages->count())
        @foreach ($chapter->pages->sortBy('page_number') as $page)
            <div class="page-container bg-gray-50 p-4 rounded flex justify-center">
                <img src="{{ asset($page->image_url) }}" alt="Page {{ $page->page_number }}"
                    class="mx-auto max-w-full h-auto reader-img">
            </div>
        @endforeach
    @else
        <div class="text-gray-500">Halaman tidak tersedia untuk chapter ini.</div>
    @endif
</div>

@push('scripts')
    <script src="{{ asset('js/component/floatingContolPanel.js') }}"></script>
@endpush
