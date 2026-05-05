@extends('layouts.app')

@section('title', 'OlehLampung — Oleh-oleh Autentik Lampung, Kirim ke Mana Saja')

@section('content')
{{-- Hero Section --}}
<section class="bg-cream">
    <div class="container-main pt-20 pb-32 md:pt-28 md:pb-40">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <div class="animate-fade-in-up">
                <h1 class="font-heading text-4xl md:text-5xl font-extrabold text-navy leading-tight mb-4">
                    Oleh-oleh Autentik Lampung,<br>
                    <span class="text-amber">Kirim ke Mana Saja.</span>
                </h1>
                <p class="text-gray-500 text-lg mb-10 max-w-md">
                    Pilihan terbaik keripik pisang, kopi robusta, dan kerajinan Tapis untuk mahasiswa perantau dan pecinta kuliner.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Belanja Sekarang
                </a>
            </div>
            <div class="relative animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="rounded-2xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=600&h=400&fit=crop" alt="Siger Lampung" class="w-full h-80 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white rounded-xl p-3 shadow-lg animate-fade-in" style="animation-delay: 0.5s">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-amber-light rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Produk Terjual</p>
                            <p class="font-bold text-navy text-sm">2,500+</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Kategori Unggulan --}}
<section class="py-24">
    <div class="container-main">
        <h2 class="section-title">Kategori Unggulan</h2>
        <p class="section-subtitle mb-12">Jelajahi koleksi terbaik dari Lampung</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            @foreach($categories as $category)
                <a href="{{ route('products.category', $category->slug) }}" class="category-card group">
                    <img src="{{ $category->image ?: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=300&fit=crop' }}" alt="{{ $category->name }}">
                    <div class="category-overlay">
                        <span class="category-name group-hover:translate-y-0 transform translate-y-1 transition-transform">{{ $category->name }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Produk Pilihan --}}
<section class="py-20 bg-gray-50">
    <div class="container-main">
        <h2 class="section-title">Produk Pilihan</h2>
        <p class="section-subtitle mb-12">Terbaru dan terpopuler dari koleksi kami</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="card group" id="product-card-{{ $product->id }}">
                    <a href="{{ route('products.show', $product) }}">
                        <div class="product-image-wrapper">
                            <img src="{{ $product->primary_image }}" alt="{{ $product->name }}">
                            <div class="overlay"></div>
                            @if($product->is_sale && $product->discount_percent > 0)
                                <span class="badge badge-sale absolute top-3 left-3">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>
                    </a>
                    <div class="p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1.5">{{ $product->category->name }}</p>
                        <a href="{{ route('products.show', $product) }}" class="font-semibold text-navy text-sm hover:text-amber transition-colors line-clamp-2 mb-2.5 block leading-snug">
                            {{ $product->name }}
                        </a>
                        <div class="flex items-center gap-1 mb-2.5">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->rating))
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 empty" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs text-gray-400">({{ $product->review_count }})</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="price">{{ $product->formatted_price }}</span>
                            @if($product->original_price)
                                <span class="price-original">{{ $product->formatted_original_price }}</span>
                            @endif
                        </div>
                        <button
                            onclick="addToCart({{ $product->id }})"
                            class="btn btn-outline btn-sm btn-block"
                            id="add-to-cart-{{ $product->id }}"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Tambah
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="btn btn-dark">
                Lihat Semua Produk
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function addToCart(productId) {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById('cart-badge');
            badge.textContent = data.cartCount;
            badge.style.display = 'flex';
            showToast(data.message, 'success');
        }
    })
    .catch(() => showToast('Gagal menambahkan ke keranjang', 'error'));
}

function showToast(message, type) {
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('show'));
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}
</script>
@endpush
