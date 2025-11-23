@extends('layouts.app')

@section('content')
    {{-- Main Content - User's Favorite Manhwa Library --}}
    <main class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-12">
        {{-- Header Section --}}
        <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <i class="bi bi-heart-fill text-red-500"></i>
                        Library
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Koleksi manhwa favorit Anda
                    </p>
                </div>

                @auth
                    <a href="{{ route('explore.index') }}"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <i class="bi bi-plus-lg"></i>
                        <span>Jelajahi Manhwa</span>
                    </a>
                @endauth
            </div>
        </div>

        @if (isset($guest) && $guest)
            <div class="py-20 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-indigo-50 rounded-full mb-6">
                    <i class="bi bi-person-lock text-4xl text-indigo-400"></i>
                </div>
                <h2 class="text-2xl font-semibold mb-3">Masuk untuk melihat Library Anda</h2>
                <p class="text-gray-600 max-w-md mx-auto mb-6">Simpan dan kelola manhwa favorit, lanjutkan membaca terakhir,
                    dan dapatkan rekomendasi personal setelah login.</p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('login') }}"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="px-6 py-3 bg-white border border-indigo-600 text-indigo-600 hover:bg-indigo-50 rounded-lg font-medium transition">Daftar</a>
                    <a href="{{ route('explore.index') }}"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">Jelajahi
                        Manhwa</a>
                </div>
            </div>
        @else
            {{-- Results Count --}}
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Manhwa Favorit</h2>
                <div class="text-sm text-gray-600">{{ $favorites->total() }} manhwa difavoritkan</div>
            </div>
            <x-manhwa-card :manhwa="$favorites" />
        @endif
    </main>

    {{-- Toast Notification Container --}}
    <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>

    {{-- JavaScript for Remove Favorite --}}
    <script src="{{ asset('js/public/library.js') }}"></script>
@endsection
