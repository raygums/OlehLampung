@extends('layouts.app')
@section('title', isset($searchQuery) ? 'Hasil Pencarian: '.$searchQuery : (isset($currentCategory) ? $currentCategory->name : 'Katalog Produk').' — OlehLampung')
@section('content')

{{-- Category Header --}}
<section class="category-header">
    <div class="container-main">
        <div class="category-header-inner">
            <div class="category-header-info">
                @if(isset($searchQuery) && $searchQuery)
                    <p class="category-header-label">Hasil pencarian</p>
                    <h1 class="category-header-title">"{{ $searchQuery }}" <span class="category-header-count">— {{ $products->total() }} produk</span></h1>
                @elseif(isset($currentCategory))
                    <p class="category-header-label">Kategori</p>
                    <h1 class="category-header-title">{{ $currentCategory->name }} <span class="category-header-count">— {{ $products->total() }} produk</span></h1>
                @else
                    <h1 class="category-header-title">Katalog Produk <span class="category-header-count">— {{ $products->total() }} produk</span></h1>
                @endif
            </div>
            <form action="{{ route('products.search') }}" method="GET" class="category-search-form">
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

{{-- Main Content --}}
<div class="container-main catalog-main">
    <div class="catalog-layout">

        {{-- Sidebar Filter --}}
        <aside class="catalog-sidebar">
            <div class="catalog-filter-card">
                <form id="filter-form" method="GET" action="{{ isset($searchQuery) ? route('products.search') : (isset($currentCategory) ? route('products.category', $currentCategory->slug) : route('products.index')) }}">
                    @if(isset($searchQuery))<input type="hidden" name="q" value="{{ $searchQuery }}">@endif

                    {{-- Kategori Filter --}}
                    <div class="filter-section">
                        <h3 class="filter-title">Kategori</h3>
                        <div class="filter-options">
                            @foreach($categories as $cat)
                                <label class="filter-option">
                                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="filter-checkbox" {{ in_array($cat->id, request()->get('categories', [])) ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span class="filter-option-label">{{ $cat->name }}</span>
                                    <span class="filter-option-count">{{ $cat->products_count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Harga Filter --}}
                    <div class="filter-section">
                        <h3 class="filter-title">Rentang Harga</h3>
                        <div class="filter-price-inputs">
                            <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="form-input">
                            <span class="filter-price-divider">—</span>
                            <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="form-input">
                        </div>
                        <button type="submit" class="btn btn-outline btn-sm btn-block mt-5">Terapkan</button>
                    </div>

                    {{-- Rating Filter --}}
                    <div class="filter-section">
                        <h3 class="filter-title">Rating</h3>
                        <div class="filter-options">
                            @foreach([5,4,3,2] as $r)
                                <label class="filter-option">
                                    <input type="radio" name="min_rating" value="{{ $r }}" class="filter-radio" {{ request('min_rating')==$r?'checked':'' }} onchange="this.form.submit()">
                                    <div class="stars">@for($i=1;$i<=5;$i++)<svg class="w-3.5 h-3.5 {{ $i<=$r?'':'empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor</div>
                                    <span class="text-xs text-gray-500">{{ $r }}.0+</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ isset($currentCategory) ? route('products.category', $currentCategory->slug) : route('products.index') }}" class="btn btn-sm btn-block text-gray-500 border border-gray-300 hover:bg-gray-100">Reset Filter</a>
                </form>
            </div>
        </aside>

        {{-- Product Grid --}}
        <div class="catalog-content">
            <div class="catalog-toolbar">
                <p class="catalog-toolbar-info">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</p>
                <select name="sort" form="filter-form" onchange="this.form.submit()" class="form-select catalog-toolbar-sort">
                    <option value="newest" {{ request('sort')=='newest'?'selected':'' }}>Terbaru</option>
                    <option value="price_low" {{ request('sort')=='price_low'?'selected':'' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort')=='price_high'?'selected':'' }}>Harga Tertinggi</option>
                    <option value="popular" {{ request('sort')=='popular'?'selected':'' }}>Terpopuler</option>
                    <option value="rating" {{ request('sort')=='rating'?'selected':'' }}>Rating Tertinggi</option>
                </select>
            </div>

            @if($products->count())
                <div class="product-grid">
                    @foreach($products as $product)
                        @include('products._card', ['product' => $product])
                    @endforeach
                </div>
                <div class="catalog-pagination">{{ $products->links() }}</div>
            @else
                <div class="catalog-empty">
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
