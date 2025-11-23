@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Tambah Featured Manhwa</h1>
            <a href="{{ route('admin.featured-manhwa.index') }}" class="text-blue-500 hover:underline">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <form action="{{ route('admin.featured-manhwa.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Pilih Manhwa</label>
                    <select name="manhwa_id" class="w-full px-3 py-2 border rounded" required>
                        <option value="">-- Pilih Manhwa --</option>
                        @foreach ($manhwas as $manhwa)
                            <option value="{{ $manhwa->id }}">{{ $manhwa->title }}</option>
                        @endforeach
                    </select>
                    @error('manhwa_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Order (Urutan tampil)</label>
                    <input type="number" name="order" value="{{ $maxOrder + 1 }}" class="w-full px-3 py-2 border rounded"
                        min="0" required>
                    @error('order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Urutan tampil di hero banner (0 = paling depan)</p>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                        <span class="text-sm">Active (tampilkan di hero banner)</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.featured-manhwa.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
