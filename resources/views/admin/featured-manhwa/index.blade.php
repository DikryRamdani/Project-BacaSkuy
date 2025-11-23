@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Featured Manhwa Management</h1>
            <a href="{{ route('admin.featured-manhwa.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <i class="bi bi-plus-circle"></i> Tambah Featured
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cover</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($featuredManhwas as $featured)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.featured-manhwa.update', $featured) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="order" value="{{ $featured->order }}"
                                        class="w-16 px-2 py-1 border rounded" min="0">
                                    <input type="hidden" name="is_active" value="{{ $featured->is_active ? '1' : '0' }}">
                                    <button type="submit" class="text-blue-500 hover:text-blue-700 ml-2">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ $featured->manhwa->cover_url }}" alt="{{ $featured->manhwa->title }}"
                                    class="h-16 w-12 object-cover rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $featured->manhwa->title }}</div>
                                <div class="text-sm text-gray-500">{{ $featured->manhwa->author }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.featured-manhwa.toggle', $featured) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1 rounded text-sm {{ $featured->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $featured->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.featured-manhwa.destroy', $featured) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada featured manhwa. Tambahkan sekarang!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/featuredManhwaIndex.js') }}"></script>
@endpush
