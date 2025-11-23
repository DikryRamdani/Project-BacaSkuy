@extends('layouts.app')

@section('content')
    @php
        $q = $q ?? '';
        $sortBy = $sortBy ?? 'title';
        $genres = $genres ?? [];
        $status = $status ?? '';
        $year = $year ?? '';
    @endphp

    {{-- Main Content - Explore All Manhwa --}}
    <main class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-12">
        {{-- Header Section - Search Bar + Sort Dropdown + Filters --}}
        <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold">Jelajahi Semua Manhwa</h1>
                <button id="toggle-filters" type="button"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="bi bi-funnel"></i>
                    <span class="text-sm">Filter</span>
                    <i class="bi bi-chevron-down transition-transform" id="filter-chevron"></i>
                </button>
            </div>

            {{-- Advanced Filters (collapsible) --}}
            <div id="advanced-filters" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                <form method="GET" action="{{ route('explore.index') }}" id="filter-form">
                    <input type="hidden" name="sort" value="{{ $sortBy }}">
                    <input type="hidden" name="q" value="{{ $q }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Genre Filter --}}
                        <div>
                            <label class="block text-sm font-medium mb-2">Genre</label>
                            <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3 bg-white">
                                @foreach ($allGenres as $genre)
                                    <label class="flex items-center gap-2 mb-2 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                                            {{ in_array($genre->id, $genres) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm">{{ $genre->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-sm font-medium mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white">
                                <option value="">Semua Status</option>
                                <option value="Ongoing" {{ $status === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="Completed" {{ $status === 'Completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="Hiatus" {{ $status === 'Hiatus' ? 'selected' : '' }}>Hiatus</option>
                            </select>
                        </div>

                        {{-- Year Filter --}}
                        <div>
                            <label class="block text-sm font-medium mb-2">Tahun</label>
                            <select name="year" class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white">
                                <option value="">Semua Tahun</option>
                                @foreach ($availableYears as $availableYear)
                                    <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                        {{ $availableYear }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                            <i class="bi bi-check2 mr-1"></i>
                            Terapkan Filter
                        </button>
                        <a href="{{ route('explore.index') }}"
                            class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                            <i class="bi bi-x-circle mr-1"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Search & Sort Controls --}}
            <form method="GET" action="{{ route('explore.index') }}" class="flex flex-col sm:flex-row gap-3">
                @foreach ($genres as $genreId)
                    <input type="hidden" name="genres[]" value="{{ $genreId }}">
                @endforeach
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="hidden" name="year" value="{{ $year }}">
                {{-- Search Bar --}}
                <div class="flex-1 relative">
                    <input type="text" name="q" value="{{ $q }}"
                        placeholder="Cari judul, penulis, genre..."
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        autocomplete="off" />
                    @if ($q)
                        <a href="{{ route('explore.index', ['sort' => $sortBy]) }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            title="Clear search">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>

                {{-- Sort Dropdown --}}
                <div class="flex gap-3">
                    <select name="sort" onchange="this.form.submit()"
                        class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                        <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>A-Z</option>
                        <option value="title_desc" {{ $sortBy === 'title_desc' ? 'selected' : '' }}>Z-A</option>
                        <option value="rating" {{ $sortBy === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="favorites" {{ $sortBy === 'favorites' ? 'selected' : '' }}>Paling Difavoritkan
                        </option>
                        <option value="latest" {{ $sortBy === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    </select>

                    {{-- Search Button --}}
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10.99 9.99z" />
                        </svg>
                        <span class="hidden sm:inline">Cari</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Results Count --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">
                @if ($q)
                    Hasil Pencarian untuk "{{ $q }}"
                @else
                    Semua Manhwa
                @endif
            </h2>
            <div class="text-sm text-gray-600">
                Menampilkan {{ $manhwas->total() }} seri
            </div>
        </div>

        {{-- Manhwa Grid --}}
        <x-manhwa-card :manhwa="$manhwas" />
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/public/exploreFilter.js') }}"></script>
@endpush
