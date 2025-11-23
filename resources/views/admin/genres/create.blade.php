@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <h1 class="text-lg font-semibold mb-4">Tambah Genre</h1>
        <div class="bg-white rounded shadow-sm p-4">
            <form action="{{ route('admin.genre.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Genre</label>
                    <input type="text" name="name" class="w-full mt-1 border rounded p-2" required>
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
                    <a href="{{ route('admin.genre.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
