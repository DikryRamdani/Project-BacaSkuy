<header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center gap-4">
                <a href="/" class="text-xl sm:text-2xl font-bold">BacaSkuy</a>
                <nav class="hidden md:flex items-center gap-3 text-sm text-gray-600">
                    <a href="{{ route('home') }}"
                        class="hover:underline {{ request()->routeIs('home') ? 'text-indigo-600 font-semibold' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('explore.index') }}"
                        class="hover:underline {{ request()->routeIs('explore.*') ? 'text-indigo-600 font-semibold' : '' }}">
                        Explore
                    </a>
                    <a href="{{ route('library.index') }}"
                        class="hover:underline {{ request()->routeIs('library.*') ? 'text-indigo-600 font-semibold' : '' }}">
                        Library
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:block relative" style="min-width:320px;">
                    <input id="search-input" type="text" name="q" placeholder="Cari judul, penulis, genre..."
                        class="w-full pr-12 px-3 py-2 rounded-md border border-gray-300 bg-white text-sm text-gray-800"
                        autocomplete="off" />

                    {{-- Search Button --}}
                    <button id="search-btn" type="button" aria-label="Search"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center bg-white border border-gray-300 rounded-full text-gray-600 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10.99 9.99z" />
                        </svg>
                    </button>

                    {{-- Suggestions --}}
                    <div id="search-suggestions"
                        class="hidden absolute z-50 mt-2 left-0 right-0 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                    </div>
                </div>

                {{-- Mobile Search --}}
                <button id="mobile-search-btn"
                    class="sm:hidden w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center focus:outline-none"
                    title="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10.99 9.99z" />
                    </svg>
                </button>

                <div class="flex items-center gap-2">
                    {{-- Profile Menu --}}
                    <div class="relative">
                        <button id="profile-btn" aria-haspopup="true" aria-expanded="false"
                            class="focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full" title="Profil">
                            <x-img-profile />
                        </button>

                        <div id="profile-menu"
                            class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="py-3 px-4">
                                @auth
                                    <div class="text-sm font-medium">Halo, {{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                                    <div class="border-t border-gray-100 my-2"></div>
                                    <a href="{{ route('settings.index') }}"
                                        class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0a1.724 1.724 0 002.575 1.10l.189-.105a1.724 1.724 0 012.351.678l.63 1.205a1.724 1.724 0 01-.28 1.87l-.14.14a1.724 1.724 0 00-.44 1.66c.188.7-.063 1.435-.623 1.885l-.14.11a1.724 1.724 0 00-.48 1.73l.18 1.02a1.724 1.724 0 01-1.32 2.0l-1.26.21a1.724 1.724 0 00-1.57.99l-.36.76a1.724 1.724 0 01-2.98 0l-.36-.76a1.724 1.724 0 00-1.57-.99l-1.26-.21a1.724 1.724 0 01-1.32-2.0l.18-1.02a1.724 1.724 0 00-.48-1.73l-.14-.11c-.56-.45-.811-1.18-.623-1.885a1.724 1.724 0 00-.44-1.66l-.14-.14a1.724 1.724 0 01-.28-1.87l.63-1.205a1.724 1.724 0 012.351-.678l.189.105c.94.52 2.16.18 2.575-1.10z" />
                                        </svg>
                                        <span class="text-sm">Pengaturan</span>
                                    </a>
                                    @if (auth()->user()->is_admin)
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7h18M3 12h18M3 17h18" />
                                            </svg>
                                            <span class="text-sm text-indigo-600">Admin</span>
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-2 py-2 text-sm text-red-600 hover:bg-gray-50 rounded">Logout</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                        <span class="text-sm text-indigo-600">Login</span>
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                                        <span class="text-sm">Daftar</span>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Mobile Search Modal --}}
<div id="mobile-search-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 sm:hidden">
    <div class="bg-white p-4">
        <div class="flex items-center gap-2 mb-4">
            <button id="close-mobile-search" class="text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <input id="mobile-search-input" type="text" placeholder="Cari judul, penulis, genre..."
                class="flex-1 px-4 py-2 rounded-md border border-gray-300 bg-white text-sm text-gray-800"
                autocomplete="off" />
        </div>
        <div id="mobile-search-results" class="max-h-96 overflow-y-auto"></div>
    </div>
</div>

{{-- Mobile Bottom Nav --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40 pb-safe">
    <div class="flex justify-around items-center h-16">
        <a href="{{ route('home') }}"
            class="flex flex-col items-center justify-center flex-1 {{ request()->routeIs('home') ? 'text-indigo-600' : 'text-gray-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-xs mt-1 font-medium">Home</span>
        </a>
        <a href="{{ route('explore.index') }}"
            class="flex flex-col items-center justify-center flex-1 {{ request()->routeIs('explore.*') ? 'text-indigo-600' : 'text-gray-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10.99 9.99z" />
            </svg>
            <span class="text-xs mt-1 font-medium">Explore</span>
        </a>
        <a href="{{ route('library.index') }}"
            class="flex flex-col items-center justify-center flex-1 {{ request()->routeIs('library.*') ? 'text-indigo-600' : 'text-gray-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="text-xs mt-1 font-medium">Library</span>
        </a>
    </div>
</nav>

<script>
    // Set routes for navbar.js
    window.routes = {
        home: '{{ route('home') }}',
        searchSuggest: '{{ route('search.suggest') }}'
    };
</script>
<script src="{{ asset('js/component/navbar.js') }}"></script>
