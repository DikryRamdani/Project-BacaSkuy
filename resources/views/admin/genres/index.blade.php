@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Manajemen Genre</h1>
            <a href="{{ route('admin.genre.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Tambah Genre</a>
        </div>

        <div class="bg-white rounded shadow-sm p-4">
            @if ($genres->count())
                <ul class="divide-y">
                    @foreach ($genres as $g)
                        <li class="py-2 flex items-center justify-between">
                            <div>{{ $g->name }}</div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.genre.edit', $g->id) }}" class="text-sm text-blue-600">Edit</a>
                                <form action="{{ route('admin.genre.destroy', $g->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm text-red-600">Hapus</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-gray-500">Belum ada genre.</div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/genresIndex.js') }}"></script>
@endpush
