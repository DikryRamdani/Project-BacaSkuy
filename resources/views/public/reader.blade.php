@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-12">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold mt-2">{{ $chapter->title ?? 'Chapter ' . $chapter->chapter_number }}</h1>
                <div class="text-sm text-gray-500">{{ $chapter->manhwa->title ?? '' }} &middot;
                    {{ $chapter->created_at ? $chapter->created_at->format('Y-m-d') : '' }}</div>
            </div>
        </div>

        <x-floating-control-panel :chapter="$chapter" />

        {{-- <x-next-prev-btn :chapter="$chapter" /> --}}
    </div>
@endsection
