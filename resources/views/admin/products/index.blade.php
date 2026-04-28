@extends('layouts.admin')
@section('page-title', 'Manajemen Produk')
@section('content')
<div class="flex items-center justify-between mb-6">
    <form class="flex gap-3" method="GET">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="form-input text-sm w-64">
        <select name="category" class="form-select text-sm w-auto" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Tambah Produk
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Produk</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Kategori</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Harga</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Stok</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
                <th class="text-right text-xs font-semibold text-gray-500 uppercase px-5 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->primary_image }}" class="w-10 h-10 rounded-lg object-cover">
                            <div>
                                <p class="font-semibold text-navy text-sm">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $product->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3"><span class="badge badge-category">{{ $product->category->name }}</span></td>
                    <td class="px-5 py-3 text-sm font-semibold">{{ $product->formatted_price }}</td>
                    <td class="px-5 py-3 text-sm">
                        <span class="{{ $product->stock < 10 ? 'text-danger font-semibold' : 'text-gray-700' }}">{{ $product->stock }}</span>
                    </td>
                    <td class="px-5 py-3">
                        @if($product->is_active)
                            <span class="badge bg-green-100 text-green-700">Aktif</span>
                        @else
                            <span class="badge bg-gray-100 text-gray-500">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-gray-400 hover:text-amber transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button class="text-gray-400 hover:text-danger transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada produk</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $products->links() }}</div>
@endsection
