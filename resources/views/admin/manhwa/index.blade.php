@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">Manhwa — Admin</h1>
            <a href="{{ route('admin.manhwa.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Manhwa</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div id="manhwa-grid" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($manhwa as $m)
                <div
                    class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-transparent hover:border-indigo-500">
                    <img src="{{ $m->cover_url ? $m->cover_url : asset('images/placeholder.png') }}"
                        alt="{{ $m->title }}"
                        class="w-full h-48 object-cover transition duration-300 group-hover:brightness-90">
                    <div class="p-3">
                        <h2 class="font-semibold transition-colors duration-300 group-hover:text-indigo-600">
                            {{ $m->title }}</h2>
                        <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($m->description, 100) }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <a href="{{ route('admin.manhwa.edit', $m) }}" class="text-sm text-blue-600">Edit</a>
                            <form action="{{ route('admin.manhwa.destroy', $m) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $manhwas->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/manhwaIndex.js') }}"></script>
@endpush
