@extends('layouts.app')

@section('content')
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-12">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Pengaturan Akun</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        {{-- Profile Picture Section --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Foto Profil</h2>

            <div class="flex items-center gap-6">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center"
                        id="profile-image-preview">
                        @if ($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <span
                                class="text-3xl font-bold text-gray-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="absolute bottom-0 right-0 bg-indigo-600 rounded-full p-2 cursor-pointer hover:bg-indigo-700"
                        onclick="document.getElementById('profile-image-input').click()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <input type="file" id="profile-image-input" class="hidden" accept="image/*"
                        onchange="uploadProfileImage(event)">
                </div>

                <div class="flex-1">
                    <p class="text-sm text-gray-600 mb-3">
                        Format: JPG, PNG, GIF (Max 2MB)
                    </p>
                    @if ($user->profile_image)
                        <button onclick="removeProfileImage()" class="text-sm text-red-600 hover:text-red-700 font-medium">
                            Hapus Foto
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Username Section --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Username</h2>

            <form id="username-form" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pengguna
                    </label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Minimal 3 karakter</p>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                    Simpan Username
                </button>
            </form>
        </div>

        {{-- Email Section --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Email</h2>

            <form id="email-form" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                    Simpan Email
                </button>
            </form>
        </div>

        {{-- Password Section --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Ubah Password</h2>

            <form id="password-form" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Saat Ini
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <input type="password" id="new_password" name="new_password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                    Ubah Password
                </button>
            </form>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-red-600 mb-2">Zona Berbahaya</h2>
            <p class="text-sm text-gray-600 mb-4">
                Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan.
            </p>
            <button onclick="confirmDeleteAccount()"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                Hapus Akun
            </button>
        </div>
    </main>

    {{-- Toast Notification Container --}}
    <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>

    <script src="{{ asset('js/public/settings.js') }}"></script>
@endsection
