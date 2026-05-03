@extends('layouts.app')
@section('title', isset($searchQuery) ? 'Hasil Pencarian: '.$searchQuery : (isset($currentCategory) ? $currentCategory->name : 'Katalog Produk').' — OlehLampung')
@section('content')
<section class="bg-amber-50 border-b border-amber-light">
    <div class="container-main py-5">
        <div class="flex flex-col md:flex-row items-center gap-4">
            @if(isset($searchQuery) && $searchQuery)
                <p class="text-sm text-gray-600">Hasil pencarian "<strong>{{ $searchQuery }}</strong>" — {{ $products->total() }} produk</p>
            @elseif(isset($currentCategory))
                <p class="text-sm text-gray-600">Kategori: <strong>{{ $currentCategory->name }}</strong> — {{ $products->total() }} produk</p>
            @else
                <p class="text-sm text-gray-600">Menampilkan <strong>{{ $products->total() }}</strong> produk</p>
            @endif
            <form action="{{ route('products.search') }}" method="GET" class="flex-1 max-w-md ml-auto">
                <div class="flex">
                    <input type="text" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Cari produk..." class="form-input rounded-r-none border-r-0 text-sm">
                    <button type="submit" class="btn btn-primary rounded-l-none px-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
<div class="container-main py-10">
    <div class="flex flex-col md:flex-row gap-8">
        <aside class="w-full md:w-72 shrink-0">
            <div class="bg-white rounded-xl border border-gray-200 p-6 sticky top-20">
                <form id="filter-form" method="GET" action="{{ isset($searchQuery) ? route('products.search') : (isset($currentCategory) ? route('products.category', $currentCategory->slug) : route('products.index')) }}">
                    @if(isset($searchQuery))<input type="hidden" name="q" value="{{ $searchQuery }}">@endif
                    <div class="filter-section">
                        <h3 class="filter-title">Kategori</h3>
                        @foreach($categories as $cat)
                            <label class="flex items-center gap-3 cursor-pointer text-sm mb-3">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="w-4 h-4 rounded border-gray-300 text-amber" {{ in_array($cat->id, request()->get('categories', [])) ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>{{ $cat->name }}</span>
                                <span class="ml-auto text-xs text-gray-400">{{ $cat->products_count }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="filter-section">
                        <h3 class="filter-title">Rentang Harga</h3>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="form-input text-xs py-2">
                            <span class="text-gray-400">—</span>
                            <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="form-input text-xs py-2">
                        </div>
                        <button type="submit" class="btn btn-outline btn-sm btn-block mt-4">Terapkan</button>
                    </div>
                    <div class="filter-section">
                        <h3 class="filter-title">Rating</h3>
                        @foreach([5,4,3,2] as $r)
                            <label class="flex items-center gap-3 cursor-pointer text-sm mb-3">
                                <input type="radio" name="min_rating" value="{{ $r }}" class="w-4 h-4 text-amber" {{ request('min_rating')==$r?'checked':'' }} onchange="this.form.submit()">
                                <div class="stars">@for($i=1;$i<=5;$i++)<svg class="w-3.5 h-3.5 {{ $i<=$r?'':'empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor</div>
                                <span class="text-xs text-gray-500">{{ $r }}.0+</span>
                            </label>
                        @endforeach
                    </div>
                    <a href="{{ isset($currentCategory) ? route('products.category', $currentCategory->slug) : route('products.index') }}" class="btn btn-sm btn-block text-gray-500 border border-gray-300 hover:bg-gray-100 mt-2">Reset Filter</a>
                </form>
            </div>
        </aside>
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</p>
                <select name="sort" form="filter-form" onchange="this.form.submit()" class="form-select text-sm py-2 w-auto">
                    <option value="newest" {{ request('sort')=='newest'?'selected':'' }}>Terbaru</option>
                    <option value="price_low" {{ request('sort')=='price_low'?'selected':'' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort')=='price_high'?'selected':'' }}>Harga Tertinggi</option>
                    <option value="popular" {{ request('sort')=='popular'?'selected':'' }}>Terpopuler</option>
                    <option value="rating" {{ request('sort')=='rating'?'selected':'' }}>Rating Tertinggi</option>
                </select>
            </div>
            @if($products->count())
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @include('products._card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-10">{{ $products->links() }}</div>
            @else
                <div class="text-center py-20">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <h3 class="font-heading font-semibold text-navy text-lg mb-2">Produk tidak ditemukan</h3>
                    <p class="text-gray-500 text-sm">Coba kata kunci lain atau reset filter.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function addToCart(productId) {
    fetch('{{ route("cart.add") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }, body: JSON.stringify({ product_id: productId, quantity: 1 }) })
    .then(r => r.json()).then(data => { if(data.success){ document.getElementById('cart-badge').textContent=data.cartCount; document.getElementById('cart-badge').style.display='flex'; showToast(data.message,'success'); }});
}
function showToast(m,t){ const e=document.querySelector('.toast'); if(e)e.remove(); const d=document.createElement('div'); d.className='toast toast-'+t; d.textContent=m; document.body.appendChild(d); requestAnimationFrame(()=>d.classList.add('show')); setTimeout(()=>{d.classList.remove('show');setTimeout(()=>d.remove(),400)},3000); }
</script>
@endpush
