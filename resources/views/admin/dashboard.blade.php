@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold mb-4">Admin Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.manhwa.index') }}" class="p-4 bg-white rounded shadow hover:shadow-md">
                <h3 class="font-semibold">Manhwa</h3>
                <p class="text-sm text-gray-600">Kelola seri manhwa (tambah, edit, hapus)</p>
            </a>

            <a href="{{ route('admin.chapter.index') }}" class="p-4 bg-white rounded shadow hover:shadow-md">
                <h3 class="font-semibold">Chapter</h3>
                <p class="text-sm text-gray-600">Kelola chapter</p>
            </a>

            <a href="{{ route('admin.genre.index') }}" class="p-4 bg-white rounded shadow hover:shadow-md">
                <h3 class="font-semibold">Genre</h3>
                <p class="text-sm text-gray-600">Kelola genre</p>
            </a>

            <a href="{{ route('admin.featured-manhwa.index') }}" class="p-4 bg-white rounded shadow hover:shadow-md">
                <h3 class="font-semibold">Featured Manhwa</h3>
                <p class="text-sm text-gray-600">Kelola featured manhwa</p>
            </a>
        </div>
    </div>
@endsection
